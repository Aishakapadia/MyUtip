<?php namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\TicketDeleteRequest;
use App\Http\Requests\Backend\TicketStatusUpdateRequest;
use App\Http\Requests\Backend\TicketStoreRequest;
use App\Http\Requests\Backend\TicketUpdateRequest;
use App\Lane;
use App\Mail\KEmail;
use App\Material;
use App\Module;
use App\Setting;
use App\Site;
use App\Status;
use App\Ticket;
use App\TicketDetail;
use App\Transporter;
use App\TransporterStatus;
use App\Unit;
use App\User;
use App\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use PermissionHelper;
use Illuminate\Support\Facades\Config;
use App\Mail\TicketCreated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\TicketFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class BackendTicketController extends BackendController
{
    private $downloadMode;
    private $module;

    public function __construct()
    {
        parent::__construct();
        set_time_limit(8000000);
        $this->downloadMode = false;
        $this->module = Module::where('url', $this->getModuleUrl())->first();
    }

    /**
     * Detail page
     *
     * @param Ticket $ticket
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDetail(Ticket $ticket)
    {
        //dd(Ticket::isTicketApprovedByAdmin($ticket));
        //TODO:: check if ticket is approved by admin and allowed to current user.

        $module = $this->module;
        $pageMode = 'Detail';

        $material_type = $this->dataMaterialTypes($ticket);
        $material = $this->dataMaterials($ticket);
        $material_code = $this->dataMaterialsCode($ticket);
        $quantity = $this->dataQuantities($ticket);
        $unit = $this->dataUnits($ticket);
        $weight = $this->dataWeights($ticket);
        $po_number = $this->dataPONumbers($ticket);

        $user = User::find(\Auth::user()->id);
        $transporter_id = null;
        if ($user->transporters()->count() > 0) {
            $transporter_id = $user->transporters->first()->id;
        }

        //$transporterInformation = $ticket->relationTransporters()->where('transporter_id', $transporter_id)->get();

        $transporterInformation = $ticket->relationTransporters;
        if ($user->isTransporter()) {
            $transporterInformation = $ticket->relationTransporters()->where('transporter_id', $transporter_id)->get();
        }

        $current_ticket_status_id = $ticket->relationActiveStatus()->id;
        //dump($current_ticket_status_id);

        $loadStatusIds = [];
        switch ($current_ticket_status_id) {
            case 1:
                $loadStatusIds = [
                    Config::get('constants.CANCEL_BY_ADMIN'),
                    Config::get('constants.APPROVE_BY_ADMIN')
                ];
                break;

            case 2:
                $loadStatusIds = [];
                break;

            case 3:
                $loadStatusIds = [Config::get('constants.ACCEPT_BY_TRANSPORTER')];
                break;

            case 4:
                $loadStatusIds = [Config::get('constants.CONFIRM_TRANSPORTER_BY_ADMIN')];
                break;

            case 5:
                $loadStatusIds = [Config::get('constants.VEHICLE_ARRIVED_BY_SUPPLIER')];
                break;

            case 6:
                $loadStatusIds = [
                    Config::get('constants.CANCELLED_BY_SUPPLIER'),
                    Config::get('constants.VEHICLE_APPROVED_BY_SUPPLIER')
                ];
                break;

            case 7:
                $loadStatusIds = [Config::get('constants.UPDATED_BY_TRANSPORTER')];
                break;

            case 8:
                $loadStatusIds = [
                    Config::get('constants.CANCELLED_BY_SUPPLIER'),
                    Config::get('constants.VEHICLE_APPROVED_BY_SUPPLIER')
                ];
                break;

            case 9:
                $loadStatusIds = [
                    Config::get('constants.DELIVERY_CHALLAN_UPDATED_BY_SUPPLIER'),
                ];
                break;

            case 10:
                $loadStatusIds = [
                    Config::get('constants.VEHICLE_LOADED_BY_SUPPLIER'),
                ];
                break;

            case 11:
                $loadStatusIds = [
                    Config::get('constants.VEHICLE_REACHED_AT_DESTINATION_BY_SITE_TEAM')
                ];
                break;

            case 12:
                $loadStatusIds = [
                    Config::get('constants.VEHICLE_OFFLOADED_BY_SITE_TEAM')
                ];
                break;
        }

        $statuses = Status::where('role_id', $user->role_id)
            ->whereIn('id', $loadStatusIds)
            ->pluck('title', 'id')
            ->prepend('Select', '');

        $transporters = Transporter::getTransportersWhoHasBid($ticket)
            ->pluck('name', 'transporter_id')
            ->prepend('Select', '');
        $isAdmin = $user->isAdmin() ? "TRUE" : "FALSE";
        $files = DB::select("SELECT * FROM ticket_files WHERE ticket_id =".$ticket->id);

        return view(admin_view('tickets.detail'), compact(
                'module',
                'pageMode',
                'material_type',
                'material',
                'material_code',
                'quantity',
                'unit',
                'weight',
                'po_number',
                'ticket',
                'statuses',
                'transporterInformation',
                'transporters',
                'files'
            )
        );
    }

    public function putStatus(TicketStatusUpdateRequest $request, $id)
    {
//        dd($request->all());
        $ticket = Ticket::find($id);
        $currentStatus = $ticket->relationActiveStatus()->id;
        $newStatus = $request->status_id;

        //parameter for Notify middleware
        request()->session()->put("middleware_ticket",$ticket);
        request()->session()->put("middleware_notify_status",$newStatus);


        $transporter_id = null;
        $user = User::find(\Auth::user()->id);
        if ($user->transporters()->count() > 0) {
            $transporter_id = $user->transporters->first()->id;
        }

        //save uploaded file and update database
        if($request->file('file_upload')){
            $fileName = $ticket->ticket_number.'_'.time().
            '.'.$request->file('file_upload')->getClientOriginalExtension();

            $request->file('file_upload')->move(
                base_path() . '/public/uploads/files/', $fileName
            );

            $ticketFile = new TicketFile();
            $ticketFile->user_id = $user->id;
            $ticketFile->ticket_id = $ticket->id;
            $ticketFile->file = $fileName;
            $ticketFile->save();
        }

        switch (\Auth::user()->role_id) {
            case Config::get('constants.ROLE_ID_SUPPLIER'):

                if ($newStatus == Config::get('constants.VEHICLE_APPROVED_BY_SUPPLIER')) {
                    $ticket->relationTransporters()->where('transporter_status_id', Config::get('constants.ACCEPTED_BY_ADMIN'))->update([
                        'transporter_status_id' => Config::get('constants.ACCEPTED_BY_SUPPLIER'),
                    ]);

//                    $ticket->relationTransporters()->where('transporter_status_id', Config::get('constants.WIP'))->update([
//                        'transporter_status_id' => Config::get('constants.ON_HOLD'),
//                    ]);

                    \DB::table('ticket_transporter')->where('ticket_id', $ticket->id)->where('transporter_status_id', Config::get('constants.WIP'))->delete();
                    \DB::table('ticket_transporter')->where('ticket_id', $ticket->id)->where('transporter_status_id', Config::get('constants.BID_SUBMITTED'))->delete();
                    \DB::table('ticket_transporter')->where('ticket_id', $ticket->id)->where('transporter_status_id', Config::get('constants.ON_HOLD'))->delete();
                }

                // Supplier updating DC Number
                if ($request->delivery_challan_number) {
                    $ticket->delivery_challan_number = $request->delivery_challan_number;
                }

                break;

            case Config::get('constants.ROLE_ID_ADMIN'):

                if ($currentStatus == Config::get('constants.CANCEL_BY_ADMIN')) {

                    // //region Emails
                    // /*** Send Email to Admins */
                    // $adminUsers = User::where('role_id', Config::get('constants.ROLE_ID_ADMIN'))->get();
                    // if ($adminUsers->count()) {
                    //     foreach ($adminUsers as $adminUser) {
                    //         $emailData['ticket'] = $ticket;
                    //         $emailData['subject'] = 'A ticket ' . $ticket->ticket_number . ' has been cancelled';
                    //         $emailData['message'] = 'Hi Admin, <br> <br> ' . 'A ticket ' . $ticket->ticket_number . ' has been cancelled.';
                    //         ENV('MAIL_ON', true) ? Mail::to($adminUser->email)->send(new KEmail($emailData)) : '';
                    //     }
                    // }
                    // //endregion
                }

                if ($currentStatus == Config::get('constants.ACCEPT_BY_TRANSPORTER')) {

                    // Admin accepting a transporter.
                    if ($request->transporter_id) {
                        $ticket->relationTransporters()->where('transporter_id', $request->transporter_id)->update([
                            'transporter_status_id' => Config::get('constants.ACCEPTED_BY_ADMIN'),
                        ]);
                    }

                }

                break;

            case Config::get('constants.ROLE_ID_TRANSPORTER'):

                if ($currentStatus == Config::get('constants.APPROVE_BY_ADMIN')) {

                    // Transporter bidding
                    if ($request->vehicle_number && $request->driver_contact) {
                        $ticket->relationTransporters()->where('transporter_id', $transporter_id)->update([
                            'vehicle_number'        => $request->vehicle_number,
                            'driver_contact'        => $request->driver_contact,
                            'eta'                   => date('Y-m-d H:i:s', strtotime($request->eta)),
                            'transporter_status_id' => Config::get('constants.BID_SUBMITTED'),
                        ]);
                    }

                }

                break;
        }

        $ticket->save();

        $ticket->relationStatuses()->attach(
            [
                $request->status_id => [
                    'user_id'  => \Auth::user()->id,
                    'comments' => $request->comments,
                ]
            ]
        );

        /**
         * Shoot an Email
         */
//        if ($request->verified_by_admin) {
        //            Mail::to($page->email)->send(new ProfileActivated($page));
        //        }

        //$page->updateProfileBackend($request, $page);

        $request->session()->flash('alert-success', 'Ticket has been updated successfully.');

        return redirect(route('ticket-detail.ticket', $id));
    }

    public function getMaterialForm()
    {
        $materials = Material::getMaterialsForDropDown()->prepend('Select', '');
        $units = Unit::getUnitsForDropDown()->prepend('Select', '');

        return view(admin_view('tickets.jq_material_form'), compact('materials', 'units'));
    }

    /**
     * Ticket create form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCreate()
    {
        $module = $this->module;
        $pageMode = 'Create';
        $vehicle_types = VehicleType::getVehicleTypesForDropDown()->prepend('Select', '');
        $units = Unit::getUnitsForDropDown()->prepend('Select', '');
        $sites = Site::getDropDown()->prepend('Select', '');
//        $fromSites = Site::getFromDropDown()->prepend('Select', '');
//        $toSites = Site::getToDropDown()->prepend('Select', '');
        $drop_off_sites = Site::getDropDown();

//        \Cache::forget('materials_data_for_dd');
//        $materials = \Cache::remember('materials_data_for_dd', 60, function () {
//            return Material::getMaterialsForDropDown()->prepend('Select', '');
//        });
        $materials = \Cache::rememberForever('materials_data_for_dd', function () {
            return Material::getMaterialsForDropDown()->prepend('Select', '');
        });

        return view(admin_view('tickets.create-edit'), compact(
            'module',
            'pageMode',
            'vehicle_types',
            'sites',
            'drop_off_sites',
            'units',
            'materials'
        ));
    }


    /**
     * Create Ticket.
     *
     * @param TicketStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postCreate(TicketStoreRequest $request)
    {
//        print_r($request->all());
//        exit();
        $siteFrom = $request->site_id_from;
        $siteTo = $request->site_id_to;
        $user_id = \Auth::user()->id;
        $userEmail = \Auth::user()->email;

        // Get related lane
        $lane = Lane::where('site_id_from', $siteFrom)
            ->where('site_id_to', $siteTo)
            ->first();

        if ($lane == NULL) {
            $request->session()->flash('alert-danger', 'No lane found please manage your lane first.');
            return redirect(route('ticket-create'));
        }
        //endregion

        $ticket = new Ticket();
        $ticket->user_id = $user_id;
        $ticket->ticket_number = 'REQ' . str_pad(Ticket::orderBy('id', 'DESC')->withTrashed()->pluck('id')->first() + 1, 8, '0', STR_PAD_LEFT);
        $ticket->vehicle_type_id = $request->vehicle_type_id;
        $ticket->site_id_from = $siteFrom;
        $ticket->site_id_to = $siteTo;
        $ticket->remarks = $request->remarks;
        $ticket->draft = $request->action == 'save' ? 1 : 0;
        $ticket->vehicle_required_at = date('Y-m-d H:i:s', strtotime($request->vehicle_required_at));
        $ticket->save();

        $ticket->relationDropOffSites()->attach($request->ticket_drop_off_site_list);

        $ticket->relationTransporters()->attach($lane->transporters->pluck('id'), ['transporter_status_id' => 1]);

        // initialize with status (open)
        $ticket->relationStatuses()->sync([1 => ['user_id' => $user_id]]); // 1 = open

        /**
         * Update Ticket's Detail
         */
        foreach ($request->material_id as $key => $row) {
            $detail = new TicketDetail();
            $detail->ticket_id = $ticket->id;
            $detail->material_id = $row;
            //$detail->material_type = $request->material_type[$key];
            $detail->unit_id = $request->unit_id[$key];
            $detail->quantity = $request->quantity[$key];
            $detail->weight = $request->weight[$key];
            $detail->po_number = $request->po_number[$key];
            $detail->ibd_number = $request->ibd_number[$key];
            $ticket->details()->save($detail);
        }


        //parameter for Notify middleware
        request()->session()->put("middleware_ticket",$ticket);
        request()->session()->put("middleware_notify_status",$request->action == 'save' ? 0 : 1);


        // //region Emails
        // /*** Send Email to Supplier (Request Creator) */
        // $emailData['ticket'] = $ticket;
        // $emailData['subject'] = 'SUPPLIER: ' . 'Ticket created ' . $ticket->ticket_number;
        // $emailData['message'] = 'Hi Supplier, <br> Your ticket has been created.';
        // ENV('MAIL_ON', true) ? Mail::to($userEmail)->send(new KEmail($emailData)) : '';


        // /*** Send Email to Admins */
        // $adminUsers = User::where('role_id', Config::get('constants.ROLE_ID_ADMIN'))->get();
        // if ($adminUsers->count()) {
        //     foreach ($adminUsers as $adminUser) {
        //         $emailData['ticket'] = $ticket;
        //         $emailData['subject'] = 'ADMIN: ' . 'Ticket created ' . $ticket->ticket_number;
        //         $emailData['message'] = 'Hi Admin, <br> Your ticket has been created.';
        //         ENV('MAIL_ON', true) ? Mail::to($adminUser->email)->send(new KEmail($emailData)) : '';
        //     }
        // }


        // /*** Send Email to Transporters */
        // foreach ($lane->transporters->pluck('id') as $transporter_id) {
        //     $transporter = Transporter::find($transporter_id);
        //     if ($transporter->users()->count()) {
        //         foreach ($transporter->users as $transporter) {
        //             $emailData['ticket'] = $ticket;
        //             $emailData['subject'] = 'TRANSPORTER: ' . 'Ticket created ' . $ticket->ticket_number;
        //             $emailData['message'] = 'Hi Transporter, <br> Your ticket has been created.';
        //             ENV('MAIL_ON', true) ? Mail::to($transporter->email)->send(new KEmail($emailData)) : '';
        //         }
        //     }
        // }


        $request->session()->flash('alert-success', 'Ticket has been added successfully.');

        if ($request->ajax()) {
            return response()->json(['success' => true, 'msg' => 'Successfully Added']);
        }

        return redirect(route('ticket-manage'));
    }


    /**
     * Ticket edit form.
     * @param Ticket $ticket
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEdit(Ticket $ticket)
    {
        $pageMode = 'Edit';
        $module = $this->module;

        $vehicle_types = VehicleType::getVehicleTypesForDropDown()->prepend('Select', '');
        $materials = Material::getMaterialsForDropDown()->prepend('Select', '');
        $units = Unit::getUnitsForDropDown()->prepend('Select', '');
        $sites = Site::getDropDown()->prepend('Select', '');
//        $fromSites = Site::getFromDropDown()->prepend('Select', '');
//        $toSites = Site::getToDropDown()->prepend('Select', '');
        $drop_off_sites = Site::getDropDown();

        //dump($ticket->details());

        return view(admin_view('tickets.create-edit'), compact(
            'module',
            'pageMode',
            'vehicle_types',
            'sites',
//            'fromSites',
//            'toSites',
            'drop_off_sites',
            'materials',
            'units',
            'ticket'
        ));
    }

    /**
     * Update page.
     *
     * @param  PageUpdateRequest
     * @param  id
     * @return redirect
     */
    public function putUpdate(TicketUpdateRequest $request, $id)
    {
//        print_r($request->all());
//        exit();
        $siteFrom = $request->site_id_from;
        $siteTo = $request->site_id_to;
        $user_id = \Auth::user()->id;

        // Get related lane
        $lane = Lane::where('site_id_from', $siteFrom)
            ->where('site_id_to', $siteTo)
            ->first();

        if ($lane == NULL) {
            $request->session()->flash('alert-danger', 'No lane found please manage your lane first.');
            return redirect(route('ticket-create'));
        }

        $ticket = Ticket::find($id);
        $ticket->user_id = $user_id;
        //$ticket->ticket_number = 'REQ' . str_pad(Ticket::orderBy('id', 'DESC')->pluck('id')->first() + 1, 8, '0', STR_PAD_LEFT);
        $ticket->vehicle_type_id = $request->vehicle_type_id;
        $ticket->site_id_from = $siteFrom;
        $ticket->site_id_to = $siteTo;
        $ticket->remarks = $request->remarks;
        $ticket->draft = $request->action == 'save' ? 1 : 0;
        $ticket->vehicle_required_at = date('Y-m-d H:i:s', strtotime($request->vehicle_required_at));
        $ticket->save();

        $ticket->relationDropOffSites()->sync($request->ticket_drop_off_site_list);

        $ticket->relationTransporters()->sync($lane->transporters->pluck('id'), ['transporter_status_id' => 1]);

        // initialize with status (open)
        $ticket->relationStatuses()->sync([1 => ['user_id' => $user_id]]); // 1 = open

        /**
         * Update Ticket's Detail
         */
        \DB::table('ticket_details')->where('ticket_id', $ticket->id)->delete();

        foreach ($request->material_id as $key => $row) {
            $detail = new TicketDetail();
            $detail->ticket_id = $ticket->id;
            $detail->material_id = $row;
            //$detail->material_type = $request->material_type[$key];
            $detail->unit_id = $request->unit_id[$key];
            $detail->quantity = $request->quantity[$key];
            $detail->weight = $request->weight[$key];
            $detail->po_number = $request->po_number[$key];
            $detail->ibd_number = $request->ibd_number[$key];
            $ticket->details()->save($detail);
        }

        //parameter for Notify middleware
        request()->session()->put("middleware_ticket",$ticket);
        request()->session()->put("middleware_notify_status",$request->action == 'save' ? 0 : 1);

        $request->session()->flash('alert-success', 'Ticket has been updated successfully.');

        if ($request->ajax()) {
            return response()->json(['success' => true, 'msg' => 'Successfully Updated']);
        }

        return redirect(route('ticket-manage'));
    }

    /**
     * Delete Ticket
     * @param TicketDeleteRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postDelete(TicketDeleteRequest $request)
    {
        $ticket = Ticket::find($request->id);

        // //region Emails
        // /*** Send Email to Admins */
        // $adminUsers = User::where('role_id', Config::get('constants.ROLE_ID_ADMIN'))->get();
        // if ($adminUsers->count()) {
        //     foreach ($adminUsers as $adminUser) {
        //         $emailData['ticket'] = $ticket;
        //         $emailData['subject'] = 'A ticket ' . $ticket->ticket_number . ' has been deleted';
        //         $emailData['message'] = 'Hi Admin, <br> <br> ' . 'A ticket ' . $ticket->ticket_number . ' has been deleted.';
        //         ENV('MAIL_ON', true) ? Mail::to($adminUser->email)->send(new KEmail($emailData)) : '';
        //     }
        // }
        // //endregion

        //parameter for Notify middleware
        request()->session()->put("middleware_ticket",$ticket);
        request()->session()->put("middleware_notify_status",Config::get('constants.DELETE_TICKET'));


        $ticket->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'msg' => 'successfully deleted']);
        }

        $request->session()->flash('alert-success', 'Ticket has been deleted successfully.');

        return redirect(route('ticket-manage'));
    }

    /**
     * Group actions for multiple selected rows.
     *
     * @param  Request
     * @return json OR redirect
     */
    public function postGroupAction(Request $request)
    {
        if ($request->ids && $request->action != '') {
            $ids = $request->ids;
            switch ($request->action) {
                case 'delete':
                    Ticket::whereIn('id', $ids)->delete();
                    $msg = 'Selected data has been deleted successfully';
                    break;

                case 'inactive':
                    Ticket::whereIn('id', $ids)->update(['active' => 0]);
                    $msg = 'Selected data has been inactive successfully';
                    break;

                case 'active':
                    Ticket::whereIn('id', $ids)->update(['active' => 1]);
                    $msg = 'Selected data has been active successfully';
                    break;

                default:
                    # code...
                    break;
            }

            if ($request->ajax()) {
                return response()->json(['success' => true, 'msg' => $msg]);
            }

            $request->session()->flash('alert-success', 'Selected data has been deleted successfully.');
            return redirect(route('page-manage'));
        }
    }

    /**
     * Page listing.
     *
     * @param  Request
     * @return view
     */
    public function getManage(Request $request)
    {
        $module = $this->module;
        $sites = Site::getDropDown()->prepend('All', '');
        $statuses = Status::getDropDown()->prepend('All', '');

        //dump(\Auth::user()->id);
//        $user = User::find(\Auth::user()->id);
//        dump($user->transporters->first()->pivot->user_id);


        // Show the page
        return view(admin_view('tickets.manage'), compact('module', 'sites', 'statuses'));
    }

    /**
     * Generate options for datatable call and export.
     *
     * @param  array
     * @return array
     */
    private function getSearchOptions($formFields = array())
    {
        $options = array();
        $keys = [
            'start',
            'length',
            'filterBy',
            'order',
            'action',
        ];

        $fields_parents = [];
        $fields_with_kids = [];
        foreach (Ticket::$module_fields as $key => $field) {
            $fields_with_kids[] = $key;
            $fields_parents[] = $key;
            if (!empty($field['multiple'])) {
                $fields_with_kids[] = $field['multiple'][0];
                $fields_with_kids[] = $field['multiple'][1];
            }
        }

        $keys = array_merge($keys, $fields_with_kids);

        // mapping options with expected keys.
        foreach ($keys as $key) {
            $options[$key] = array_key_exists($key, $formFields) ? $formFields[$key] : Input::get($key);
        }

        // changing options if download mode is set.
        if ($this->downloadMode) {
            $options['start'] = 0;
            $options['length'] = -1;
        }

        // mapping columns with fields
        $order = $options['order'];
        $options['orderByDirection'] = $order[0]['dir'];
        if ($fields_parents[$order[0]['column']]) {
            $options['orderBy'] = $fields_parents[$order[0]['column']];
        }
        return $options;
    }

    /**
     * Datatable listing call.
     *
     * @param  Request
     * @return array
     */
    public function postSearchData(Request $request)
    {
        //\Log::info(['all posted' => $request->all()]);
        $options = $this->getSearchOptions();
        $searchData = Ticket::getTickets($options);
        $response = [
            'draw'            => '',
            'recordsTotal'    => 0,
            'data'            => [],
            'recordsFiltered' => 0,
        ];

        if (!$searchData || !($searchData['total'] > 0)) {
            return $response;
        }

        $iTotalRecords = $searchData['total'];
        $sEcho = intval(Input::get('draw'));
        $records = array();
        $records["data"] = array();

        foreach ($searchData['dataset'] as $i => $data) {

            $user = User::find(\Auth::user()->id);
            $transporter_id = null;
            if ($user->transporters()->count() > 0) {
                $transporter_id = $user->transporters->first()->id;
                //$user_id = $user->transporters->first()->pivot->user_id;
            }

            $ticket = Ticket::find($data->id);

            $records["data"][] = [
                '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]" type="checkbox" class="checkboxes" value="' . $data->id . '"/><span></span></label>',
                '<a href="' . admin_url("/ticket/detail/" . $data->id) . '">' . $data->ticket_number . '</a>',
                '<a href="' . admin_url("/ticket/detail/" . $data->id) . '">' . $data->vehicle_type . '</a>',
                $data->site_from,
                $data->site_to,
                $this->dataTransporters($ticket, $transporter_id),
                $this->dataVehicleNumbers($ticket, $transporter_id),
                $this->dataDriverContact($ticket, $transporter_id),
                $this->dataEta($ticket, $transporter_id),
                $this->dataTransporterStatus($ticket, $transporter_id),
                $data->delivery_challan_number,
                '<span class="label label-success">' . $ticket->relationActiveStatus()->visible . '</span>',
                $data->remarks,
//                $data->active ? '<span class="label label-sm label-success">Active</span>' : '<span class="label label-sm label-danger">Inactive</span>',
                date(Setting::getStandardDateFormat(), strtotime($data->created_at)),
                $this->__actionColumn($data),
            ];
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        return $records;
    }

    private function dataTransporters(Ticket $ticket, $transporter_id = null)
    {
        $output = 'N/A';

        if ($ticket->relationTransporters()->count()) {

            if ($transporter_id) {
                $data = $ticket->relationTransporters()->where('transporter_id', $transporter_id)->get();
            } else {
                $data = $ticket->relationTransporters;
            }

            if ($data) {
                if ($this->downloadMode !== TRUE) {
                    $output = '<ul>';
                    foreach ($data as $row) {
                        $output .= '<li style="background: ' . TransporterStatus::find($row->pivot->transporter_status_id)->color_code . '">';
                        $output .= $row->title;
                        $output .= '</li>';
                    }
                    $output .= '</ul>';
                } else {
                    $output = "";
                    $count = count($data);
                    foreach ($data as $key => $row) {
                        $output .= $row->title;
                        if ($key != $count - 1) {
                            $output .= "\r\n";
                        }
                    }
                }
            }

        }

        return $output;
    }

    private function dataVehicleNumbers(Ticket $ticket, $transporter_id = null)
    {
        $output = 'N/A';

        if ($ticket->relationTransporters()->count()) {

            if ($transporter_id) {
                $data = $ticket->relationTransporters()->where('transporter_id', $transporter_id)->get();
            } else {
                $data = $ticket->relationTransporters;
            }

            if ($data) {
                if ($this->downloadMode !== TRUE) {
                    $output = '<ul>';
                    foreach ($data as $row) {
                        $output .= '<li style="background: ' . TransporterStatus::find($row->pivot->transporter_status_id)->color_code . '">';
                        $output .= $row->pivot->vehicle_number ? $row->pivot->vehicle_number : 'NA';
                        $output .= '</li>';
                    }
                    $output .= '</ul>';
                } else {
                    $output = "";
                    $count = count($data);
                    foreach ($data as $key => $row) {
                        $output .= $row->pivot->vehicle_number ? $row->pivot->vehicle_number : 'NA';
                        if ($key != $count - 1) {
                            $output .= "\r\n";
                        }
                    }
                }
            }

        }

        return $output;
    }

    private function dataDriverContact(Ticket $ticket, $transporter_id = null)
    {
        $output = 'N/A';

        if ($ticket->relationTransporters()->count()) {

            if ($transporter_id) {
                $data = $ticket->relationTransporters()->where('transporter_id', $transporter_id)->get();
            } else {
                $data = $ticket->relationTransporters;
            }

            if ($data) {
                if ($this->downloadMode !== TRUE) {
                    $output = '<ul>';
                    foreach ($data as $row) {
                        $output .= '<li style="background: ' . TransporterStatus::find($row->pivot->transporter_status_id)->color_code . '">';
                        $output .= $row->pivot->driver_contact ? $row->pivot->driver_contact : 'NA';
                        $output .= '</li>';
                    }
                    $output .= '</ul>';
                } else {
                    $output = "";
                    $count = count($data);
                    foreach ($data as $key => $row) {
                        $output .= $row->pivot->driver_contact ? $row->pivot->driver_contact : 'NA';
                        if ($key != $count - 1) {
                            $output .= "\r\n";
                        }
                    }
                }
            }
        }

        return $output;
    }

    private function dataEta(Ticket $ticket, $transporter_id = null)
    {
        $output = 'N/A';

        if ($transporter_id) {
            $data = $ticket->relationTransporters()->where('transporter_id', $transporter_id)->get();
        } else {
            $data = $ticket->relationTransporters;
        }

        if ($ticket->relationTransporters()->count()) {
            if ($data) {
                if ($this->downloadMode !== TRUE) {
                    $output = '<ul>';
                    foreach ($data as $row) {
                        $output .= '<li style="background: ' . TransporterStatus::find($row->pivot->transporter_status_id)->color_code . '">';
                        $output .= $row->pivot->eta ? date(\App\Setting::getStandardDateTimeFormat(), strtotime($row->pivot->eta)) : 'NA';
                        $output .= '</li>';
                    }
                    $output .= '</ul>';
                } else {
                    $output = "";
                    $count = count($data);
                    foreach ($data as $key => $row) {
                        $output .= $row->pivot->eta ? date(\App\Setting::getStandardDateTimeFormat(), strtotime($row->pivot->eta)) : 'NA';
                        if ($key != $count - 1) {
                            $output .= "\r\n";
                        }
                    }
                }
            }
        }

        return $output;
    }

    private function dataTransporterStatus(Ticket $ticket, $transporter_id = null)
    {
        $output = 'N/A';

        if ($transporter_id) {
            $data = $ticket->relationTransporters()->where('transporter_id', $transporter_id)->get();
        } else {
            $data = $ticket->relationTransporters;
        }

        if ($ticket->relationTransporters()->count()) {
            if ($data) {
                if ($this->downloadMode !== TRUE) {
                    $output = '<ul>';
                    foreach ($data as $row) {
                        $output .= '<li style="background: ' . TransporterStatus::find($row->pivot->transporter_status_id)->color_code . '">';
                        $output .= $row->pivot->transporter_status_id ? TransporterStatus::find($row->pivot->transporter_status_id)->title : 'NA';
                        $output .= '</li>';
                    }
                    $output .= '</ul>';
                } else {
                    $output = "";
                    $count = count($data);
                    foreach ($data as $key => $row) {
                        $output .= $row->pivot->transporter_status_id ? TransporterStatus::find($row->pivot->transporter_status_id)->title : 'NA';
                        if ($key != $count - 1) {
                            $output .= "\r\n";
                        }
                    }
                }
            }
        }

        return $output;
    }

    private function dataMaterialTypes(Ticket $ticket)
    {
        $output = 'N/A';

        if ($ticket->details()->count()) {
            $output = '<ul>';
            foreach ($ticket->details as $row) {
                $output .= '<li>';
                $output .= $row->material_type;
                $output .= '</li>';
            }
            $output .= '</ul>';
        }

        return $output;
    }

    private function dataMaterials(Ticket $ticket)
    {
        $output = 'N/A';

        if ($ticket->details()->count()) {
            $output = '<ul>';
            foreach ($ticket->details as $row) {
                $output .= '<li>';
                $output .= $row->material;
                $output .= '</li>';
            }
            $output .= '</ul>';
        }

        return $output;
    }

    private function dataMaterialsCode(Ticket $ticket)
    {
        $output = 'N/A';

        if ($ticket->details()->count()) {
            $output = '<ul>';
            foreach ($ticket->details as $row) {
                $output .= '<li>';
                $output .= $row->material_code;
                $output .= '</li>';
            }
            $output .= '</ul>';
        }

        return $output;
    }

    private function dataQuantities(Ticket $ticket)
    {
        $output = 'N/A';

        if ($ticket->details()->count()) {
            $output = '<ul>';
            foreach ($ticket->details as $row) {
                $output .= '<li>';
                $output .= $row->quantity;
                $output .= '</li>';
            }
            $output .= '</ul>';
        }

        return $output;
    }

    private function dataUnits(Ticket $ticket)
    {
        $output = 'N/A';

        if ($ticket->details()->count()) {
            $output = '<ul>';
            foreach ($ticket->details as $row) {
                $output .= '<li>';
                $output .= $row->unit;
                $output .= '</li>';
            }
            $output .= '</ul>';
        }

        return $output;
    }

    private function dataWeights(Ticket $ticket)
    {
        $output = 'N/A';

        if ($ticket->details()->count()) {
            $total = 0;
            $output = '<ul>';
            foreach ($ticket->details as $row) {
                $output .= '<li>';
                $output .= $row->weight;
                $total += $row->weight;
                $output .= '</li>';
            }
            $output .= '</ul>';

            if ($total > 0) {
                $output .= '<hr>Total: ' . $total;
            }
        }

        return $output;
    }

    private function dataPONumbers(Ticket $ticket)
    {
        $output = 'N/A';

        if ($ticket->details()->count()) {
            $output = '<ul>';
            foreach ($ticket->details as $row) {
                $output .= '<li>';
                $output .= $row->po_number;
                $output .= '</li>';
            }
            $output .= '</ul>';
        }

        return $output;
    }

    /**
     * Export data to csv file format.
     *
     * @return csv
     */
    public function postDownload()
    {
    
        $this->downloadMode = true;
        $formFields = json_decode(urldecode(Input::get('jsonForm')), true);

        if (!$formFields) {
            return ['error' => true, 'msg' => 'negative'];
        }

        // getting data
        $options = $this->getSearchOptions($formFields);
        $options['length'] = 9999999999;
        $searchData = Ticket::getTickets($options, true);

        // if no data found
        if (!$searchData || !($searchData['total'] > 0)) {
            die('<html><script>alert("No result found.");history.back();</script></html>');
        }


        $user = User::find(\Auth::user()->id);
        $transporter_id = null;
        if ($user->transporters()->count() > 0) {
            $transporter_id = $user->transporters->first()->id;
        }


        $fieldsMap = [];
        foreach (Ticket::$module_fields as $key => $field) {
            if ($field['download']['downloadable'] == true) {
                if ($field['download']['map_field']) {
                    $fieldsMap[$field['download']['map_field']] = $field['download']['title'];
                } else {
                    $fieldsMap[$key] = $field['download']['title'];
                }
            }
        }

//         dump($fieldsMap);
//         dd($searchData);

        $searchData = $searchData['dataset'];
        foreach ($searchData as $i => $item) {

            $ticket = Ticket::find($item->id);
            $user = User::find($ticket->user_id);

            $tmp = [];
            foreach ($fieldsMap as $oldField => $newField) {
                if ($oldField == 'active') {
                    $item->$oldField = $item->$oldField ? 'Active' : 'Inactive';
                }
                if ($oldField == 'date_of_birth') {
                    $item->$oldField = date('m/d/Y', strtotime($item->$oldField));
                }
                if ($oldField == 'created_at') {
                    $username = $user ? $user->name : '';
                    $item->$oldField = "(" . $username . ") " . Carbon::parse($item->$oldField, 'UTC')->format('jS F, Y g:i a');
                }
                if ($oldField == 'transporter') {
                    $item->$oldField = $this->dataTransporters($ticket, $transporter_id);
                }
                if ($oldField == 'vehicle_number') {
                    $item->$oldField = $this->dataVehicleNumbers($ticket, $transporter_id);
                }
                if ($oldField == 'driver_contact') {
                    $item->$oldField = $this->dataDriverContact($ticket, $transporter_id);
                }
                if ($oldField == 'eta') {
                    $item->$oldField = $this->dataEta($ticket, $transporter_id);
                }
                if ($oldField == 'transporter_status') {
                    $item->$oldField = $this->dataTransporterStatus($ticket, $transporter_id);
                }
                if ($oldField == 'ticket_status') {
                    $item->$oldField = $ticket->relationActiveStatus()->visible;
                }
                $tmp[$newField] = $item->$oldField;
            }
            
            //Append additional columns
            $this->addMaterialFields($tmp,$ticket);
            $this->addTicketStatusFields($tmp,$ticket);
            $this->addRouteAmountField($tmp);
            $searchData[$i] = $tmp;

        }

        //dd($searchData);

        //*/
        // data mapping and filtering
        $filename = 'ticket-export-' . date('Ymd-His') . '.csv';
        header("Content-Disposition: attachment; filename=$filename");
        header("Cache-control: private");
        header("Content-type: text/csv");
        header("Content-transfer-encoding: binary\n");

       //Determines the order of the columns - Actual order is by columns appended 
        $order = array(0,1,2,3,13,14,15,16,17,18,4,5,19,6,12,20,21,22,23,24,9,25,26,27,28,29,30,31,8,10,11);
        $out = fopen('php://output', 'w');
       
       //Prints header for csv
        $data = array_keys($searchData[0]);
        $new = array();
        foreach($order as $index)
            $new[] = $data[$index];
        fputcsv($out, $new);

        //Print rows for csv
        foreach ($searchData as $line) {
            $new = array();
            $val = array_values($line);
            foreach($order as $index)
                $new[] = $val[$index];
            fputcsv($out, $new);
        }
        
        fclose($out);
        exit();
        //*/
    }

    private function addRouteAmountField(&$tmp){
        $tmp['Route Amount'] = "";
    }

    private function addTicketStatusFields(&$tmp,$ticket){
        $fields = ["Request Approve ON Date & Time","Transporter Bid Submited On date & Time","Admin Confirm Transporter",
        "Vehicle Arrival date & time",	"Vehicle Approved or Reject date & time",	"Vehicle Loaded date & time",
        "Total Loading Time",	"Transit Time",	"Vehicle Reached at Dest date & time",	"Vehicle Offloaded date & time",
        "Total Offloaded Time"
        ];
        foreach ($fields as $field) {
            $tmp[$field] = "";
        }

        $query = DB::select("SELECT st.status_id,s.title,u.name,st.created_at FROM status_ticket as st
        JOIN users AS u ON u.id = st.user_id
        JOIN statuses AS s ON s.id = st.status_id
        WHERE st.ticket_id = ".$ticket->id."
        ORDER BY st.created_at ASC");

        $approvedTime = "";
        $loadTime = "";
        $reachedTime = "";
        $unloadTime = "";

        foreach ($query as $q) {
            switch ($q->status_id) {
                case 3:
                    $tmp[$fields[0]] = is_null($q->created_at) ? "" : "(".$q->name.") ".Carbon::parse($q->created_at,'UTC')->format('jS F, Y g:i a');
                    break;
                case 4:
                    $tmp[$fields[1]] = is_null($q->created_at) ? "" : "(".$q->name.") ".Carbon::parse($q->created_at,'UTC')->format('jS F, Y g:i a');
                    break;
                case 5:
                    $tmp[$fields[2]] = is_null($q->created_at) ? "" : "(".$q->name.") ".Carbon::parse($q->created_at,'UTC')->format('jS F, Y g:i a');
                    break;
                case 6:
                    $tmp[$fields[3]] = is_null($q->created_at) ? "" :   $tmp[$fields[3]]."(".$q->name.") ".Carbon::parse($q->created_at,'UTC')->format('jS F, Y g:i a')."\r\n";
                    break;
                case 7:
                case 9:
                    $approvedTime = $q->created_at;
                    $tmp[$fields[4]] = is_null($q->created_at) ? "" :  $tmp[$fields[4]]."(".$q->name.") ".Carbon::parse($q->created_at,'UTC')->format('jS F, Y g:i a')."\r\n";
                    break;
                case 11:
                    $loadTime = $q->created_at;
                    $tmp[$fields[5]] = is_null($q->created_at) ? "" : "(".$q->name.") ".Carbon::parse($q->created_at,'UTC')->format('jS F, Y g:i a');
                    break;
                case 12:
                    $reachedTime = $q->created_at;
                    $tmp[$fields[8]] = is_null($q->created_at) ? "" : "(".$q->name.") ".Carbon::parse($q->created_at,'UTC')->format('jS F, Y g:i a');
                    break;
                case 13:
                    $unloadTime = $q->created_at;
                    $tmp[$fields[9]] = is_null($q->created_at) ? "" : "(".$q->name.") ".Carbon::parse($q->created_at,'UTC')->format('jS F, Y g:i a');
                    break;
                default:
                    break;
            }

            if (strlen($approvedTime) > 0 && strlen($loadTime)){
                $t1 = Carbon::parse($approvedTime,'UTC');
                $t2 = Carbon::parse($loadTime,'UTC');
                $delta = $t2->diffForHumans($t1);
                $tmp[$fields[6]] = str_replace(" after","",$delta);
            }

            
            if (strlen($reachedTime) > 0 && strlen($loadTime)){
                $t1 = Carbon::parse($loadTime,'UTC');
                $t2 = Carbon::parse($reachedTime,'UTC');
                $delta = $t2->diffForHumans($t1);
                $tmp[$fields[7]] = str_replace(" after","",$delta);
            }

            if (strlen($reachedTime) > 0 && strlen($unloadTime)){
                $t1 = Carbon::parse($reachedTime,'UTC');
                $t2 = Carbon::parse($unloadTime,'UTC');
                $delta = $t2->diffForHumans($t1);
                $tmp[$fields[10]] = str_replace(" after","",$delta);
            }
        }


        
    }

    private function addMaterialFields(&$tmp,$ticket){

        $query = DB::select("SELECT m.sap_code,m.description,m.type,td.quantity,u.title,td.weight,td.po_number
                FROM ticket_details as td
                JOIN  materials as m
                ON m.id = td.material_id
                JOIN units as u
                ON td.unit_id = u.id
                WHERE ticket_id = ".$ticket->id);
        
        $query = $query[0];
        $tmp['Material Code'] = $query->sap_code;
        $tmp['Material Description'] = $query->description;
        $tmp['Material Type'] = $query->type;
        $tmp['Quantity'] = $query->quantity;
        $tmp['Unit'] = $query->title;
        $tmp['Weight'] = $query->weight;
        $tmp['PO Number'] = $query->po_number;
    }

    /**
     * Private function for generating action column data.
     *
     * @param  collection
     * @return html
     */
    private function __actionColumn($data)
    {
        $return = '';
        if ($data) {
            if (PermissionHelper::isAllowed('ticket/detail')) {
                if ($data->draft == 0)
                    $return .= '<a href="' . route('ticket-detail.ticket', $data->id) . '" class="btn btn-circle blue btn-outline btn-action"><i class="fa fa-list-alt"></i> </a>';
            }

            if (PermissionHelper::isAllowed('ticket/edit')) {
                if ($data->draft == 1)
                    $return .= '<a href="' . route('ticket-edit.ticket', $data->id) . '" class="btn btn-circle green btn-outline btn-action"><i class="fa fa-pencil"></i> </a>';
            }

            if (PermissionHelper::isAllowed('ticket/delete')) {
                $return .= '<button class="btn btn-circle red btn-outline btn-action btn_confirmation" data-singleton="true" data-toggle="confirmation" data-placement="left" data-id="' . $data->id . '"><i class="fa fa-trash"></i></button>
        ';
            }

        }

        return $return;
    }
}
