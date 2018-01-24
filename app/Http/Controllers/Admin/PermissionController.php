<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Permission;
use Validator;

class PermissionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $permission;
    public function __construct()
    {
        $this->middleware('auth');
        $this->permission = new Permission();
    }

    /**
     * Display a listing of the permission.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {

        /*
         *  getCollection from App/Models/Permission
         *
         *  @return mixed
         * */
        $data['permissionData'] = $this->permission->getCollection();
        $data['masterManagementTab'] = "active open";
        $data['permissionTab'] = "active";
        return view('admin.permission.permissionlist', $data);
    }

    /**
     * Show the form for creating a new permission.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $data['masterManagementTab'] = "active open";
        $data['permissionTab'] = "active";
        return view('admin.permission.add', $data);
    }

    /**
     * Display the specified permission.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {

        /*
         *  get details of the specified permission. from App/Models/Permission
         *
         * @param mixed $id
         * @param string (id) fieldname
         *  @return mixed
         * */
        $data['details'] = $this->permission->getPermissionByField($id, 'id');
        $data['masterManagementTab'] = "active open";
        $data['permissionTab'] = "active";
        return view('admin.permission.edit', $data);
    }

    /**
     * Validation of add and edit action customeValidate
     *
     * @param array $data
     * @param string $mode
     * @return mixed
     */

    public function customeValidate($data, $mode)
    {
        $rules = array(
            'name' => 'required|max:100',
            'code' => 'required|max:50|unique:tbl_permission,code',
        );

        if ($mode == "edit") {
            $rules['code'] = 'required|max:20|unique:tbl_permission,code,' . $data['id'] . ',id';
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $errorRedirectUrl = "admin/permission/add";
            if ($mode == "edit") {
                $errorRedirectUrl = "admin/permission/edit/" . $data['id'];
            }
            return redirect($errorRedirectUrl)->withInput()->withErrors($validator);
        }
        return false;
    }

    /**
     * Store a newly created permission in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(request $request)
    {
        $validations = $this->customeValidate($request->all(), 'add');
        if ($validations) {
            return $validations;
        }

        $addpermission = $this->permission->addPermission($request->all());
        if ($addpermission) {
            $request->session()->flash('alert-success', trans('app.permission_add_success'));
            return redirect('admin/permission/list');
        } else {
            $request->session()->flash('alert-danger', trans('app.permission_error'));
            return redirect('admin/permission/add')->withInput();
        }
    }

    /**
     * Update the specified permission in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(request $request)
    {
        $validations = $this->customeValidate($request->all(), 'edit');
        if ($validations) {
            return $validations;
        }

        $addpermission = $this->permission->addPermission($request->all());
        if ($addpermission) {
            $request->session()->flash('alert-success', trans('app.permission_edit_success'));
            return redirect('admin/permission/list');
        } else {
            $request->session()->flash('alert-danger', trans('app.permission_error'));
            return redirect('admin/permission/edit/' . $request->get('id'))->withInput();
        }
    }
}
