<?php namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\UserDeleteRequest;
use App\Http\Requests\Backend\UserStoreRequest;
use App\Http\Requests\Backend\UserUpdateRequest;
use App\Module;
use App\Role;
use App\Setting;
use App\Transporter;
use App\Site;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Mail;
use PermissionHelper;

class BackendUserController extends BackendController
{
    private $downloadMode;
    private $module;
    public $data;

    public function __construct()
    {
        parent::__construct();
        $this->downloadMode = false;
        $this->module = Module::where('url', $this->getModuleUrl())->first();

//        $this->data['distributors'] = Distributor::select(\DB::raw("CONCAT(distributor_name, ' - ' ,distributor) AS name"), 'distributor')
//            ->orderBy('DISTRIBUTOR', 'ASC')
//            ->distinct('DISTRIBUTOR')
//            ->pluck('name', 'distributor');

//        $this->data['regions'] = Region::where('active', 1)
//            ->orderBy('title', 'ASC')
//            ->get()->pluck('title', 'id')->prepend('Select', '');
        //$this->data['roles'] = Role::getRolesExceptSuper()->prepend('Select', '');
        //$this->data['roles'] = Role::getRolesExcept([1, 2])->prepend('Select', '');
    }

    /**
     * Get user detail.
     *
     * @param  User
     * @return view
     */
    public function getDetail(User $user)
    {
        $module = $this->module;
        $pageMode = 'Detail';

        return view(admin_view('users.detail'), compact('module', 'pageMode', 'user'));
    }

    /**
     * User create form.
     *
     * @return view
     */
    public function getCreate()
    {
        $module = $this->module;
        $pageMode = 'Create';
        $roles = Role::getRolesExceptSuper()->prepend('Select', '');
        $distributors = $this->data['distributors'];
        $regions = $this->data['regions'];
        $transporters = Transporter::getTransportersForDropDown()->prepend('Select', '');
        $sites = Site::getSitesForDropDown()->prepend('Select', '');

        return view(admin_view('users.create-edit'), compact('module', 'pageMode', 'roles', 'distributors', 'regions', 'transporters', 'sites'));
    }

    /**
     * Create user.
     *
     * @param  UserStoreRequest
     * @return redirect
     */
    public function postCreate(UserStoreRequest $request)
    {
        //dd($request->all());
        $user = new User();
        $user->role_id = $request->role_id;
        $user->name = $request->name;
        $user->email = strtolower($request->email);
        $user->password = bcrypt($request->password);
        //$user->date_of_birth = date('Y-m-d', strtotime($request->date_of_birth));
        $user->active = $request->active;
        $user->mobile = $request->mobile;
        $user->save();

        if ($request->role_id == \Config::get('constants.ROLE_ID_TRANSPORTER') && !empty($request->user_transporter_list)) {
            $user->transporters()->attach($request->user_transporter_list);
        }

        if ($request->role_id == \Config::get('constants.ROLE_ID_SITE_TEAM') && !empty($request->user_site_list)) {
            $user->sites()->attach($request->user_site_list);
        }

//        if ($request->user_distributor_list) {
//            $user->distributors()->attach($request->user_distributor_list);
//        }
//
//        if ($request->role_id == \Config::get('constants.ROLE_ID_CCD') || $request->role_id == \Config::get('constants.ROLE_ID_AM') ) {
//            if ($request->user_region_list) {
//                $user->regions()->attach($request->user_region_list);
//            }
//        }

        //$user->updateProfileBackend($request, $user);

        $request->session()->flash('alert-success', 'User has been added successfully.');

        return redirect(route('user-manage'));
    }

    /**
     * User edit form.
     *
     * @param  User
     * @return view
     */
    public function getEdit(User $user)
    {
        $pageMode = 'Edit';
        $module = $this->module;
        $roles = Role::getRolesExceptSuper()->prepend('Select', '');
        $transporters = Transporter::getTransportersForDropDown()->prepend('Select', '');
        $sites = Site::getSitesForDropDown()->prepend('Select', '');

        return view(admin_view('users.create-edit'), compact('module', 'pageMode', 'roles', 'user', 'transporters', 'sites'));
    }

    /**
     * Update user.
     *
     * @param  UserUpdateRequest
     * @param  id
     * @return redirect
     */
    public function putUpdate(UserUpdateRequest $request, $id)
    {
        //dd($request->all()); 25, 31, 5
        $user = User::find($id);
        $user->role_id = $request->role_id;
        $user->name = $request->name;
        $user->email = strtolower($request->email);
//        $user->date_of_birth = date('Y-m-d', strtotime($request->date_of_birth));
        $user->active = $request->active;
        $user->mobile = $request->mobile;
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
        $user->save();

        if ($request->role_id == \Config::get('constants.ROLE_ID_TRANSPORTER') && !empty($request->user_transporter_list)) {
            $user->transporters()->sync($request->user_transporter_list);
        }

        if ($request->role_id == \Config::get('constants.ROLE_ID_SITE_TEAM') && !empty($request->user_site_list)) {
            $user->sites()->sync($request->user_site_list);
        }

//        if ($request->user_distributor_list) {
//            $user->distributors()->sync($request->user_distributor_list);
//        }
//
//        if ($request->user_region_list) {
//            $user->regions()->sync($request->user_region_list);
//        }

        /**
         * Shoot an Email
         */
//        if ($request->verified_by_admin) {
        //            Mail::to($user->email)->send(new ProfileActivated($user));
        //        }

        //$user->updateProfileBackend($request, $user);

        $request->session()->flash('alert-success', 'User has been updated successfully.');

        return redirect(route('user-manage'));
    }

    /**
     * Delete a user
     *
     * @param  UserDeleteRequest
     * @return json OR redirect
     */
    public function postDelete(UserDeleteRequest $request)
    {
        $user = User::find($request->id);
        $user->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'msg' => 'successfully deleted']);
        }

        $request->session()->flash('alert-success', 'User has been deleted successfully.');

        return redirect(route('user-manage'));
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
                    User::whereIn('id', $ids)->delete();
                    $msg = 'Selected data has been deleted successfully';
                    break;

                case 'inactive':
                    User::whereIn('id', $ids)->update(['active' => 0]);
                    $msg = 'Selected data has been inactive successfully';
                    break;

                case 'active':
                    User::whereIn('id', $ids)->update(['active' => 1]);
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
            return redirect(route('user-manage'));
        }
    }

    /**
     * User listing.
     *
     * @param  Request
     * @return view
     */
    public function getManage(Request $request)
    {
        $module = $this->module;
        //$roles = Role::getRolesExceptSuper()->prepend('All', '');
        $roles = Role::getRolesExceptSuper()->prepend('Select', '');

        // Show the page
        return view(admin_view('users.manage'), compact('module', 'roles'));
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
        foreach (User::$module_fields as $key => $field) {
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
        $searchData = User::getUsers($options);
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
                $data->role_title,
                $data->name,
                '<a href="' . admin_url("/user/edit/" . $data->id) . '">' . $data->email . '</a>',
                $data->mobile,
//                date(Setting::getStandardDateFormat(), strtotime($data->date_of_birth)),
//                $data->sort,
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
        $searchData = User::getUsers($options, true);

        // if no data found
        if (!$searchData || !($searchData['total'] > 0)) {
            die('<html><script>alert("No result found.");history.back();</script></html>');
        }

        $fieldsMap = [];
        foreach (User::$module_fields as $key => $field) {
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
        header('Content-Disposition: attachment; filename="user-export.csv"');
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
            if (PermissionHelper::isAllowed('user/detail')) {
                $return .= '<a href="' . route('user-detail.user', $data->id) . '" class="btn btn-circle blue btn-outline btn-action"><i class="fa fa-list-alt"></i> </a>';
            }

            if (PermissionHelper::isAllowed('user/edit')) {
                $return .= '<a href="' . route('user-edit.user', $data->id) . '" class="btn btn-circle green btn-outline btn-action"><i class="fa fa-pencil"></i> </a>';
            }

            if (PermissionHelper::isAllowed('user/delete')) {
                $return .= '<button class="btn btn-circle red btn-outline btn-action btn_confirmation" data-singleton="true" data-toggle="confirmation" data-placement="left" data-id="' . $data->id . '"><i class="fa fa-trash"></i></button>
        ';
            }

        }

        return $return;
    }
}
