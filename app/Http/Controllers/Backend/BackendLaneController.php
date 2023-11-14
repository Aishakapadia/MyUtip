<?php namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\LaneDeleteRequest;
use App\Http\Requests\Backend\LaneStoreRequest;
use App\Http\Requests\Backend\LaneUpdateRequest;
use App\Module;
use App\Setting;
use App\Role;
use App\Lane;
use App\Site;
use App\Transporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Mail;
use PermissionHelper;

class BackendLaneController extends BackendController
{
    private $downloadMode;
    private $module;

    public function __construct()
    {
        parent::__construct();
        $this->downloadMode = false;
        $this->module = Module::where('url', $this->getModuleUrl())->first();
    }

    /**
     * Detail page
     *
     * @param Lane $lane
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDetail(Lane $lane, $id)
    {
        $lane = Lane::find($id);
        //dd($lane->siteFrom);

        $module = $this->module;
        $pageMode = 'Detail';

        return view(admin_view('lanes.detail'), compact('module', 'pageMode', 'lane'));
    }

    /**
     * Vehicle Type create form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCreate()
    {
        $module = $this->module;
        $pageMode = 'Create';
        $sites = Site::getDropDown()->prepend('Select', '');
        $transporters = Transporter::getTransportersForDropDown();

        return view(admin_view('lanes.create-edit'), compact('module', 'pageMode', 'sites', 'transporters'));
    }


    /**
     * Create Vehicle Type.
     *
     * @param LaneStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postCreate(LaneStoreRequest $request)
    {
        $lane = new Lane();
        $lane->title = $request->title;
//        $lane->slug = $request->slug;
        $lane->description = $request->description;
        $lane->site_id_from = $request->site_id_from;
        $lane->site_id_to = $request->site_id_to;
        $lane->active = $request->active;
        $lane->sort = $request->sort;
        $lane->save();

        if ($request->lane_transporter_list) {
            $lane->transporters()->attach($request->lane_transporter_list);
        }

        //$page->updateProfileBackend($request, $page);

        $request->session()->flash('alert-success', 'Vehicle Type has been added successfully.');

        return redirect(route('lane-manage'));
    }


    /**
     * Vehicle Type edit form.
     * @param Lane $lane
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEdit(Lane $lane, $id)
    {
        $pageMode = 'Edit';
        $module = $this->module;
        $sites = Site::getDropDown()->prepend('Select', '');
        $transporters = Transporter::getTransportersForDropDown();
        $lane = Lane::find($id);

        return view(admin_view('lanes.create-edit'), compact('module', 'pageMode', 'lane', 'sites', 'transporters'));
    }

    /**
     * Update page.
     *
     * @param  PageUpdateRequest
     * @param  id
     * @return redirect
     */
    public function putUpdate(LaneUpdateRequest $request, $id)
    {
        $lane = Lane::find($id);
        $lane->title = $request->title;
//        $lane->slug = $request->slug;
        $lane->description = $request->description;
        $lane->site_id_from = $request->site_id_from;
        $lane->site_id_to = $request->site_id_to;
        $lane->active = $request->active;
        $lane->sort = $request->sort;
        $lane->save();

        if ($request->lane_transporter_list) {
            $lane->transporters()->sync($request->lane_transporter_list);
        }

        /**
         * Shoot an Email
         */
//        if ($request->verified_by_admin) {
        //            Mail::to($page->email)->send(new ProfileActivated($page));
        //        }

        //$page->updateProfileBackend($request, $page);

        $request->session()->flash('alert-success', 'Vehicle Type has been updated successfully.');

        return redirect(route('lane-manage'));
    }

    /**
     * Delete Vehicle Type
     * @param LaneDeleteRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postDelete(LaneDeleteRequest $request)
    {
        $page = Lane::find($request->id);
        $page->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'msg' => 'successfully deleted']);
        }

        $request->session()->flash('alert-success', 'Vehicle Type has been deleted successfully.');

        return redirect(route('lane-manage'));
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
                    Lane::whereIn('id', $ids)->delete();
                    $msg = 'Selected data has been deleted successfully';
                    break;

                case 'inactive':
                    Lane::whereIn('id', $ids)->update(['active' => 0]);
                    $msg = 'Selected data has been inactive successfully';
                    break;

                case 'active':
                    Lane::whereIn('id', $ids)->update(['active' => 1]);
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
        $sites = Site::getDropDown()->prepend('Select', '');

        // Show the page
        return view(admin_view('lanes.manage'), compact('module', 'sites'));
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
        foreach (Lane::$module_fields as $key => $field) {
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
        $searchData = Lane::getLanes($options);
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
            $records["data"][] = [
                '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]" type="checkbox" class="checkboxes" value="' . $data->id . '"/><span></span></label>',
                '<a href="' . admin_url("/lane/edit/" . $data->id) . '">' . $data->title . '</a>',
                //$data->slug,
                $data->site_from,
                $data->site_to,
                $this->laneTransporters($data),
                //strip_tags(limit_words($data->description, 10)),
                $data->sort,
                $data->active ? '<span class="label label-sm label-success">Active</span>' : '<span class="label label-sm label-danger">Inactive</span>',
                date(Setting::getStandardDateFormat(), strtotime($data->created_at)),
                $this->__actionColumn($data),
            ];
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        return $records;
    }

    private function laneTransporters($data)
    {
        $output = 'N/A';

        $lane = Lane::find($data->id);

        if ($lane->transporters()->count()) {
            $output = '<ol>';
            foreach ($lane->transporters as $transporter) {
                $output .= '<li>';
                $output .= $transporter->title;
                $output .= '</li>';
            }
            $output .= '</ol>';
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
        $searchData = Lane::getLanes($options, true);

        // if no data found
        if (!$searchData || !($searchData['total'] > 0)) {
            die('<html><script>alert("No result found.");history.back();</script></html>');
        }

        $fieldsMap = [];
        foreach (Lane::$module_fields as $key => $field) {
            if ($field['download']['downloadable'] == true) {
                if ($field['download']['map_field']) {
                    $fieldsMap[$field['download']['map_field']] = $field['download']['title'];
                } else {
                    $fieldsMap[$key] = $field['download']['title'];
                }
            }
        }

        // dump($fieldsMap);
        // dd($searchData);

        $searchData = $searchData['dataset'];
        foreach ($searchData as $i => $item) {
            $tmp = [];
            foreach ($fieldsMap as $oldField => $newField) {
                if ($oldField == 'active') {
                    $item->$oldField = $item->$oldField ? 'Active' : 'Inactive';
                }
                if ($oldField == 'date_of_birth') {
                    $item->$oldField = date('m/d/Y', strtotime($item->$oldField));
                }
                if ($oldField == 'created_at') {
                    $item->$oldField = date('m/d/Y h:i:sa', strtotime($item->$oldField));
                }
                $tmp[$newField] = $item->$oldField;
            }
            $searchData[$i] = $tmp;
        }

        //*/
        // data mapping and filtering
        header('Content-Disposition: attachment; filename="lane-export.csv"');
        header("Cache-control: private");
        header("Content-type: text/csv");
        header("Content-transfer-encoding: binary\n");
        $out = fopen('php://output', 'w');
        fputcsv($out, array_keys($searchData[0]));
        foreach ($searchData as $line) {
            fputcsv($out, $line);
        }
        fclose($out);
        exit();
        //*/
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
            if (PermissionHelper::isAllowed('lane/detail')) {
                $return .= '<a href="' . route('lane-detail.lane', $data->id) . '" class="btn btn-circle blue btn-outline btn-action"><i class="fa fa-list-alt"></i> </a>';
            }

            if (PermissionHelper::isAllowed('lane/edit')) {
                $return .= '<a href="' . route('lane-edit.lane', $data->id) . '" class="btn btn-circle green btn-outline btn-action"><i class="fa fa-pencil"></i> </a>';
            }

            if (PermissionHelper::isAllowed('lane/delete')) {
                $return .= '<button class="btn btn-circle red btn-outline btn-action btn_confirmation" data-singleton="true" data-toggle="confirmation" data-placement="left" data-id="' . $data->id . '"><i class="fa fa-trash"></i></button>
        ';
            }

        }

        return $return;
    }
}
