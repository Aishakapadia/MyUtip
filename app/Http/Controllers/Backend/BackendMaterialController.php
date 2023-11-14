<?php namespace App\Http\Controllers\Backend;

use App\Http\Requests\Backend\MaterialDeleteRequest;
use App\Http\Requests\Backend\MaterialStoreRequest;
use App\Http\Requests\Backend\MaterialUpdateRequest;
use App\Module;
use App\Setting;
use App\Role;
use App\Material;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Config;
use Mail;
use App\Mail\KEmail;
use PermissionHelper;

class BackendMaterialController extends BackendController
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
     * @param Material $material
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDetail(Request $request, $id = null)
    {
        $id = $id ? $id : $request->id;

        $material = Material::find($id);

        $module = $this->module;
        $pageMode = 'Detail';

        if ($request->ajax()) {
            return response()->json($material);
        }

        return view(admin_view('materials.detail'), compact('module', 'pageMode', 'material'));
    }

    /**
     * Material create form.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getCreate()
    {
        $module = $this->module;
        $pageMode = 'Create';
        $roles = Role::getRolesExceptSuper()->prepend('Select', '');

        return view(admin_view('materials.create-edit'), compact('module', 'pageMode', 'roles'));
    }


    /**
     * Create Material.
     *
     * @param MaterialStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postCreate(MaterialStoreRequest $request)
    {
        \Cache::forget('materials_data_for_dd');

        $material = new Material();
        $material->sap_code = $request->sap_code;
        $material->title = $request->title;
        $material->type = $request->type;
        $material->description = $request->description;
        $material->active = $request->active;
        $material->sort = $request->sort;
        $material->save();

        //region Emails
        /*** Send Email to Admins */
        $adminUsers = User::where('role_id', Config::get('constants.ROLE_ID_ADMIN'))->get();
        if ($adminUsers->count()) {
            foreach ($adminUsers as $adminUser) {
                $emailData['data'] = $material;
                $emailData['subject'] = 'New material has been added to myutip.com';
                $emailData['message'] = 'Hi Admin, <br> <br> ' . 'New material named: "<strong>' . $material->title . '</strong>" has been added.';
                ENV('MAIL_ON', true) ? Mail::to($adminUser->email)->send(new KEmail($emailData)) : '';
            }
        }
        //endregion

        //$page->updateProfileBackend($request, $page);

        $request->session()->flash('alert-success', 'Material has been added successfully.');

        return redirect(route('material-manage'));
    }


    /**
     * Material edit form.
     * @param Material $material
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEdit(Material $material, $id)
    {
        $pageMode = 'Edit';
        $module = $this->module;
        //$roles = Role::getRolesExceptSuper()->prepend('Select', '');
        $material = Material::find($id);

        return view(admin_view('materials.create-edit'), compact('module', 'pageMode', 'material'));
    }

    /**
     * Update page.
     *
     * @param  PageUpdateRequest
     * @param  id
     * @return redirect
     */
    public function putUpdate(MaterialUpdateRequest $request, $id)
    {
        \Cache::forget('materials_data_for_dd');

        $material = Material::find($id);
        $material->sap_code = $request->sap_code;
        $material->title = $request->title;
        $material->type = $request->type;
        $material->description = $request->description;
        $material->active = $request->active;
        $material->sort = $request->sort;
        $material->save();

        /**
         * Shoot an Email
         */
//        if ($request->verified_by_admin) {
        //            Mail::to($page->email)->send(new ProfileActivated($page));
        //        }

        //$page->updateProfileBackend($request, $page);

        $request->session()->flash('alert-success', 'Material has been updated successfully.');

        return redirect(route('material-manage'));
    }

    /**
     * Delete Material
     * @param MaterialDeleteRequest $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function postDelete(MaterialDeleteRequest $request)
    {
        $material = Material::find($request->id);

        //region Emails
        /*** Send Email to Admins */
        $adminUsers = User::where('role_id', Config::get('constants.ROLE_ID_ADMIN'))->get();
        if ($adminUsers->count()) {
            foreach ($adminUsers as $adminUser) {
                $emailData['data'] = $material;
                $emailData['subject'] = 'A material has been deleted from myutip.com';
                $emailData['message'] = 'Hi Admin, <br> <br> ' . 'A material named: "<strong>' . $material->title . '</strong>" has been deleted.';
                ENV('MAIL_ON', true) ? Mail::to($adminUser->email)->send(new KEmail($emailData)) : '';
            }
        }
        //endregion

        $material->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'msg' => 'successfully deleted']);
        }

        $request->session()->flash('alert-success', 'Material has been deleted successfully.');

        return redirect(route('material-manage'));
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
                    Material::whereIn('id', $ids)->delete();
                    $msg = 'Selected data has been deleted successfully';
                    break;

                case 'inactive':
                    Material::whereIn('id', $ids)->update(['active' => 0]);
                    $msg = 'Selected data has been inactive successfully';
                    break;

                case 'active':
                    Material::whereIn('id', $ids)->update(['active' => 1]);
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

        // Show the page
        return view(admin_view('materials.manage'), compact('module'));
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
        foreach (Material::$module_fields as $key => $field) {
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
        $searchData = Material::getMaterials($options);
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
                $data->sap_code,
                '<a href="' . admin_url("/material/edit/" . $data->id) . '">' . $data->title . '</a>',
                $data->type,
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
        $searchData = Material::getMaterials($options, true);

        // if no data found
        if (!$searchData || !($searchData['total'] > 0)) {
            die('<html><script>alert("No result found.");history.back();</script></html>');
        }

        $fieldsMap = [];
        foreach (Material::$module_fields as $key => $field) {
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
        header('Content-Disposition: attachment; filename="material-export.csv"');
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
            if (PermissionHelper::isAllowed('material/detail')) {
                $return .= '<a href="' . route('material-detail.material', $data->id) . '" class="btn btn-circle blue btn-outline btn-action"><i class="fa fa-list-alt"></i> </a>';
            }

            if (PermissionHelper::isAllowed('material/edit')) {
                $return .= '<a href="' . route('material-edit.material', $data->id) . '" class="btn btn-circle green btn-outline btn-action"><i class="fa fa-pencil"></i> </a>';
            }

            if (PermissionHelper::isAllowed('material/delete')) {
                $return .= '<button class="btn btn-circle red btn-outline btn-action btn_confirmation" data-singleton="true" data-toggle="confirmation" data-placement="left" data-id="' . $data->id . '"><i class="fa fa-trash"></i></button>
        ';
            }

        }

        return $return;
    }
}
