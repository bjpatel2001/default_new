<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Validator;

class RoleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $role;
    protected $permission;

    public function __construct()
    {
        $this->middleware('auth');
        $this->role = new Role();
        $this->permission = new Permission();
    }

    /**
     * Display a listing of the role.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {

        /*
         *  getCollection from App/Models/Role
         *
         *  @return mixed
         * */
        $data['roleData'] = $this->role->getCollection();
        $data['masterManagementTab'] = "active open";
        $data['roleTab'] = "active";
        return view('admin.role.rolelist', $data);
    }

    /**
     * Show the form for creating a new role.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        $data['masterManagementTab'] = "active open";
        $data['roleTab'] = "active";
        $data['permissionData'] = $this->permission->getCollection();
        return view('admin.role.add', $data);
    }

    /**
     * Display the specified role.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {

        /*
         *  get details of the specified role. from App/Models/Role
         *
         * @param mixed $id
         * @param string (id) fieldname
         *  @return mixed
         * */
        $data['details'] = $this->role->getRoleByField($id, 'id');
        $data['permissionData'] = $this->permission->getCollection();
        $data['masterManagementTab'] = "active open";
        $data['roleTab'] = "active";
        return view('admin.role.edit', $data);
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
            'role_type' => 'required|max:50',
            'code' => 'required|max:20|unique:tbl_user_role,code',
        );

        if ($mode == "edit") {
            $rules['code'] = 'required|max:20|unique:tbl_user_role,code,' . $data['id'] . ',id';
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $errorRedirectUrl = "admin/role/add";
            if ($mode == "edit") {
                $errorRedirectUrl = "admin/role/edit/" . $data['id'];
            }
            return redirect($errorRedirectUrl)->withInput()->withErrors($validator);
        }
        return false;
    }

    /**
     * Store a newly created role in storage.
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

        $addrole = $this->role->addRole($request->all());
        if ($addrole) {
            $request->session()->flash('alert-success', trans('app.role_add_success'));
            return redirect('admin/role/list');
        } else {
            $request->session()->flash('alert-danger', trans('app.role_error'));
            return redirect('admin/role/add')->withInput();
        }
    }

    /**
     * Update the specified role in storage.
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

        $addrole = $this->role->addRole($request->all());
        if ($addrole) {
            $request->session()->flash('alert-success', trans('app.role_edit_success'));
            return redirect('admin/role/list');
        } else {
            $request->session()->flash('alert-danger', trans('app.role_error'));
            return redirect('admin/role/edit/' . $request->get('id'))->withInput();
        }
    }
}
