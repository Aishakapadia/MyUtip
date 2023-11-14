<?php namespace App\Http\Controllers\Backend;

use App\Http\Controllers\AdminController;
use App\Permission;
use App\Settings;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\PermissionRequest;
use App\Http\Requests\Admin\PermissionEditRequest;
use App\Http\Requests\Admin\DeleteRequest;
use Illuminate\Support\Facades\Input;
use Datatables;
use DB;

class BackendPermissionController extends BackendController
{

    private $downloadMode;

    public function __construct()
    {
        parent::__construct();
        $this->downloadMode = false;
    }

    /*
    * Display a listing of the resource.
    *
    * @return Response
    */
    public function getManage()
    {
        // Show the page
        return view(admin_view('permissions.manage'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function getCreate()
    {
        $pageMode = 'Create';
        return view(admin_view('permissions.create'), compact('pageMode'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate(PermissionRequest $request)
    {
//        dd($request->all());

        $permission = new Permission();
        $permission->name = $request->name;
        $permission->email = $request->email;
        $permission->password = bcrypt($request->password);
//        $permission->gender = $request->gender;
        $permission->active = $request->active;
        $permission->save();

        $request->session()->flash('alert-success', 'Permission has been added successfully.');

        return redirect(admin_url('permission/manage'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $permission
     * @return Response
     */
    public function getEdit($id)
    {
        $pageMode = 'Edit';
        $permission = Permission::find($id);
        return view(admin_view('permissions.edit'), compact('pageMode', 'permission'));
    }

    public function putUpdate(PermissionRequest $request, $id)
    {
        $record = Permission::find($id);
        $record->name = $request->name;
        $record->email = $request->email;
        if ($request->has('password')) {
            $record->password = bcrypt($request->password);
        }
        $record->active = $request->active;
        if ($record->save() && $request->ajax()) {
            return ['result' => true];
        }

        $request->session()->flash('alert-success', 'Permission has been updated successfully.');

        return redirect(admin_url('permission/manage'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $permission
     * @return Response
     */
    public function postEdit(PermissionEditRequest $request, $id)
    {

        $permission = Permission::find($id);
        $permission->name = $request->name;
        $permission->confirmed = $request->confirmed;

        $password = $request->password;
        $passwordConfirmation = $request->password_confirmation;

        if (!empty($password)) {
            if ($password === $passwordConfirmation) {
                $permission->password = bcrypt($password);
            }
        }
        $permission->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $permission
     * @return Response
     */

    public function getDelete($id, Request $request)
    {
        $permission = Permission::find($id);
        $permission->delete();

        $request->session()->flash('alert-success', 'Permission has been deleted successfully.');

        return redirect(admin_url('permission'));

//        return view( 'admin.permissions.delete', compact( 'permission' ) );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $permission
     * @return Response
     */
    public function postDelete(DeleteRequest $request, $id)
    {
        $permission = Permission::find($id);
        $permission->delete();
    }


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
        $searchData = Permission::getPermissions($options, true);
        //dd( $searchData );

        // if no data found
        if (!$searchData || !($searchData['total'] > 0)) {
            die('<html><script>alert("No result found.");history.back();</script></html>');
        }

        $fieldsMap = [
            //'id'         => 'Permission ID',
            'name'       => 'Permissionname',
            'email'      => 'Email',
            'active'     => 'Status',
            'created_at' => 'Date',
        ];

        $searchData = $searchData['dataset'];
        //dump($searchData);
        foreach ($searchData as $i => $item) {
            $tmp = [];
            foreach ($fieldsMap as $oldField => $newField) {
                if ($oldField == 'active') {
                    $item->$oldField = $item->$oldField ? 'Active' : 'Inactive';
                }
                $tmp[$newField] = $item->$oldField;
            }
            $searchData[$i] = $tmp;
        }

        // data mapping and filtering
        header('Content-Disposition: attachment; filename="permission-export.csv"');
        header("Cache-control: private");
        header("Content-type: text/csv");
        header("Content-transfer-encoding: binary\n");
        $out = fopen('php://output', 'w');
        fputcsv($out, array_keys($searchData[0]));
        foreach ($searchData as $line) {
            fputcsv($out, $line);
        }
        fclose($out);

    }

    private function getSearchOptions($formFields = array())
    {
        $options = array();
        $keys = [
            'start',
            'length',
            'filterBy',
            'order',
            'action',
            'title',
            'slug',
            'active',
        ];

        // mapping options with expected keys.
        foreach ($keys as $key) {
            $options[$key] = array_key_exists($key, $formFields) ? $formFields[$key] : Input::get($key);
        }

        //dump($options);

        // changing options if download mode is set.
        if ($this->downloadMode) {
            $options['start'] = 0;
            $options['length'] = -1;
        }

        // mapping columns with fields
        $order = $options['order'];
        $options['orderByDirection'] = $order[0]['dir'];
        switch ($order[0]['column']) {
            case 0;
                $options['orderBy'] = 'title';
                break;
            case 1:
                $options['orderBy'] = 'email';
                break;
            case 2:
                $options['orderBy'] = 'active';
                break;
            case 3:
                $options['orderBy'] = 'created_at';
                break;
        }

        return $options;
    }

    public function postSearchData()
    {
        $options = $this->getSearchOptions();

        $searchData = Permission::getPermissions($options);
        $response = [
            'draw'            => '',
            'recordsTotal'    => 0,
            'data'            => [],
            'recordsFiltered' => 0
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
                //'<input type="checkbox" name="id[]" value="'.$id.'">',
                $data->title,
                $data->slug,
                $data->active ? 'Active' : 'Inactive',
                date(Settings::getStandardDateFormat(), strtotime($data->created_at)),
                $this->__actionColumn($data->id)
            ];
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;

        return $records;
    }

    private function __actionColumn($permissionId)
    {
        $return = '';

        $return .= '<div class="btn-group">';
        $return .=
            '<a class="btn green" href="javascript:;" data-toggle="dropdown">
                    <i class="fa fa-bars"></i> Action
                    <i class="fa fa-angle-down"></i>
                </a>';

        $return .= '<ul class="dropdown-menu pull-right">';

        if (\PermissionHelper::isAllowed('permission/edit')) {
            $return .= '<li><a href="' . \URL::to(admin_url('permission/edit/' . $permissionId)) . '"><i class="fa fa-pencil"></i> Edit </a></li>';
        }
        if (\PermissionHelper::isAllowed('permission/delete')) {
            $return .= '<li>' . anchor_delete(\URL::to(admin_url('permission/delete/' . $permissionId))) . '</li>';
        }

        $return .= '</ul></div>';

        return $return;
    }

}
