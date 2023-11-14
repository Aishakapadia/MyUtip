<?php namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\RoleDeleteRequest;
use App\Http\Requests\Backend\RoleStoreRequest;
use App\Http\Requests\Backend\RoleUpdateRequest;
use App\Module;
use App\Role;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use PermissionHelper;

class BackendRoleController extends BackendController
{
    private $downloadMode;
    private $module;

    public function __construct()
    {
        parent::__construct();
        $this->downloadMode = false;
        $this->module       = Module::where('url', $this->getModuleUrl())->first();
    }

    /**
     * Get role detail.
     *
     * @param  Role
     * @return view
     */
    public function getDetail(Role $role)
    {
        $module   = $this->module;
        $pageMode = 'Detail';

        return view(admin_view('roles.detail'), compact('module', 'pageMode', 'role'));
    }

    /**
     * Role create form.
     *
     * @return view
     */
    public function getCreate()
    {
        $module   = $this->module;
        $pageMode = 'Create';

        return view(admin_view('roles.create-edit'), compact('module', 'pageMode'));
    }

    /**
     * Create role.
     *
     * @param  RoleStoreRequest
     * @return redirect
     */
    public function postCreate(RoleStoreRequest $request)
    {
        $role              = new Role();
        $role->title       = $request->title;
        $role->slug        = $request->slug;
        $role->description = $request->description;
        $role->sort        = $request->sort;
        $role->active      = $request->active;
        $role->save();

        $request->session()->flash('alert-success', 'Role has been added successfully.');

        return redirect(route('role-manage'));
    }

    /**
     * Role edit form.
     *
     * @param  Role
     * @return view
     */
    public function getEdit(Role $role)
    {
        $pageMode = 'Edit';
        $module   = $this->module;

        return view(admin_view('roles.create-edit'), compact('module', 'pageMode', 'role'));
    }

    /**
     * Update role.
     *
     * @param  RoleUpdateRequest
     * @param  id
     * @return redirect
     */
    public function putUpdate(RoleUpdateRequest $request, $id)
    {
        $role              = Role::find($id);
        $role->title       = $request->title;
        $role->slug        = $request->slug;
        $role->description = $request->description;
        $role->sort        = $request->sort;
        $role->active      = $request->active;
        $role->save();

        $request->session()->flash('alert-success', 'Role has been updated successfully.');

        return redirect(route('role-manage'));
    }

    /**
     * Delete a role
     *
     * @param  RoleDeleteRequest
     * @return json OR redirect
     */
    public function postDelete(RoleDeleteRequest $request)
    {
        $role = Role::find($request->id);
        $role->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'msg' => 'successfully deleted']);
        }

        $request->session()->flash('alert-success', 'Role has been deleted successfully.');

        return redirect(route('role-manage'));
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
                    Role::whereIn('id', $ids)->delete();
                    $msg = 'Selected data has been deleted successfully';
                    break;

                case 'inactive':
                    Role::whereIn('id', $ids)->update(['active' => 0]);
                    $msg = 'Selected data has been inactive successfully';
                    break;

                case 'active':
                    Role::whereIn('id', $ids)->update(['active' => 1]);
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
            return redirect(route('role-manage'));
        }
    }

    /**
     * Role listing.
     *
     * @param  Request
     * @return view
     */
    public function getManage(Request $request)
    {
        $module = $this->module;
        $roles  = Role::getRolesExceptSuper()->prepend('All', '');

        // Show the page
        return view(admin_view('roles.manage'), compact('module', 'roles'));
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
        $keys    = [
            'start',
            'length',
            'filterBy',
            'order',
            'action',
        ];

        $fields_parents   = [];
        $fields_with_kids = [];
        foreach (Role::$module_fields as $key => $field) {
            $fields_with_kids[] = $key;
            $fields_parents[]   = $key;
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
            $options['start']  = 0;
            $options['length'] = -1;
        }

        // dump($fields_parents);
        // dd($options);

        // mapping columns with fields
        $order                       = $options['order'];
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
        $options    = $this->getSearchOptions();
        $searchData = Role::getRoles($options);
        $response   = [
            'draw'            => '',
            'recordsTotal'    => 0,
            'data'            => [],
            'recordsFiltered' => 0,
        ];

        if (!$searchData || !($searchData['total'] > 0)) {
            return $response;
        }

        $iTotalRecords   = $searchData['total'];
        $sEcho           = intval(Input::get('draw'));
        $records         = array();
        $records["data"] = array();

        foreach ($searchData['dataset'] as $i => $data) {
            $records["data"][] = [
                '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]" type="checkbox" class="checkboxes" value="' . $data->id . '"/><span></span></label>',
                '<a href="' . admin_url("/role/edit/" . $data->id) . '">' . $data->title . '</a>',
                $data->slug,
                $data->description,
                $data->sort,
                $data->active ? '<span class="label label-sm label-success">Active</span>' : '<span class="label label-sm label-danger">Inactive</span>',
                date(Setting::getStandardDateFormat(), strtotime($data->created_at)),
                $this->__actionColumn($data),
            ];
        }

        $records["draw"]            = $sEcho;
        $records["recordsTotal"]    = $iTotalRecords;
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
        $formFields         = json_decode(urldecode(Input::get('jsonForm')), true);

        if (!$formFields) {
            return ['error' => true, 'msg' => 'negative'];
        }

        // getting data
        $options           = $this->getSearchOptions($formFields);
        $options['length'] = 9999999999;
        $searchData        = Role::getRoles($options, true);

        // if no data found
        if (!$searchData || !($searchData['total'] > 0)) {
            die('<html><script>alert("No result found.");history.back();</script></html>');
        }

        $fieldsMap = [];
        foreach (Role::$module_fields as $key => $field) {
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
        header('Content-Disposition: attachment; filename="role-export.csv"');
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
            if (PermissionHelper::isAllowed('role/detail')) {
                $return .= '<a href="' . route('role-detail.role', $data->id) . '" class="btn btn-circle blue btn-outline btn-action"><i class="fa fa-list-alt"></i></a>';
            }

            if (PermissionHelper::isAllowed('role/edit')) {
                $return .= '<a href="' . route('role-edit.role', $data->id) . '" class="btn btn-circle green btn-outline btn-action"><i class="fa fa-pencil"></i></a>';
            }

            if (PermissionHelper::isAllowed('role/permissions')) {
                $return .= '<a href="' . route('permission.role', $data->id) . '" class="btn btn-circle yellow btn-outline btn-action"><i class="fa fa-key"></i></a>';
            }

            if (PermissionHelper::isAllowed('role/delete')) {
                $return .= '<button class="btn btn-circle red btn-outline btn-action btn_confirmation" data-singleton="true" data-toggle="confirmation" data-placement="left" data-id="' . $data->id . '"><i class="fa fa-trash"></i></button>
        ';
            }
        }

        return $return;
    }

    public function getPermissions($role_id)
    {
        /** Find out selected permissions */
        $selected_permissions = array();
        if ($role_id) {
            $role = Role::find($role_id);
            foreach ($role->modules as $module) {
                $selected_permissions[] = $module->id;
            }
        }

        $modules  = Module::all();
        $module   = $this->module;
        $pageMode = 'Create';

        return view(admin_view('roles.permissions'), compact('module', 'modules', 'selected_permissions', 'role'));
    }

    public function postPermissions(Request $request)
    {
        $role_id     = $request->role_id;
        $permissions = $request->permissions;

        $role = Role::find($role_id);

        if ($permissions) {
            /** One Way */
//        $role->modules()->detach();
            //        $role->modules()->attach($permissions, ['created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);

            /** 2nd Way */
            $sync_data = array();
            foreach ($permissions as $permission) {
                $sync_data[$permission] = ['created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')];
            }
            $role->modules()->sync($sync_data);

            $request->session()->flash('alert-success', 'Permissions have been updated successfully.');
        }

        return redirect(admin_url('role/permissions/' . $role_id));
    }
}
