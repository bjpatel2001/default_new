<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Validator;
use Event;
use Hash;
use App\Events\SendMail;


class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $user;
    protected $role;

    public function __construct(User $user, Role $role)
    {
        $this->middleware('auth');
        $this->user = $user;
        $this->role = $role;
    }

    /**
     * Display a listing of the user.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {

        if(Auth::user()->role_id != 1){
            return back();
        }
        /*
         *  getCollection from App/Models/User
         *
         *  @return mixed
         * */
        $data['userData'] = $this->user->getCollection();
        $data['roleData'] = $this->role->getCollection();
        $data['userTab'] = "active";
        return view('admin.user.userlist', $data);
    }

    public function datatable(Request $request)
    {
        // default count of user $userCount
        $userCount = 0;

        /*
         *    getDatatableCollection from App/Models/User
         *   get all users
         *
         *  @return mixed
         * */
        $userData = $this->user->getDatatableCollection();

        /*
         *    scopeGetFilteredData from App/Models/User
         *   get filterred users
         *
         *  @return mixed
         * */
        $userData = $userData->GetFilteredData($request);

        /*
         *    getUserCount from App/Models/User
         *   get count of users
         *
         *  @return integer
         * */
        $userCount = $this->user->getUserCount($userData);

        //  Sorting user data base on requested sort order
        if (isset(config('constant.userDataTableFieldArray')[$request->order['0']['column']])) {
            $userData = $userData->SortUserData($request);
        } else {
            if ($request->searchType == 'textSearch') {
                $userData = $userData->SortUserDataByRaw($issn);
            }
        }

        /*
         *  get paginated collection of user
         *
         * @param  \Illuminate\Http\Request $request
         * @return mixed
         * */
        $userData = $userData->GetUserData($request);

        $appData = array();
        foreach ($userData as $userData) {
            $row = array();
            $row[] = $userData->name;
            $row[] = $userData->email;
            $row[] = $userData->mobile_number;
            $row[] = view('datatable.switch', ['module' => "user",'status' => $userData->status, 'id' => $userData->id])->render();
            $row[] = view('datatable.action', ['module' => "user",'type' => $userData->role_id, 'id' => $userData->id])->render();
            $appData[] = $row;
        }

        return [
            'draw' => $request->draw,
            'recordsTotal' => $userCount,
            'recordsFiltered' => $userCount,
            'data' => $appData,
        ];
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Http\Response
     */

    public function create()
    {
        if(Auth::user()->role_id != 1){
            return back();
        }
        $data['userTab'] = "active";
        return view('admin.user.add', $data);
    }

    /**
     * Display the specified user.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        if(Auth::user()->role_id != 1){
            return back();
        }

        /*
         *  get details of the specified user. from App/Models/User
         *
         * @param mixed $id
         * @param string (id) fieldname
         *  @return mixed
         * */
        $data['details'] = $this->user->getUserByField($id, 'id');
        $data['userTab'] = "active";
        return view('admin.user.edit', $data);
    }

    public function profile()
    {
        /*
         *  get details of the specified user. from App/Models/User
         *
         * @param mixed $id
         * @param string (id) fieldname
         *  @return mixed
         * */
        $data['profileTab'] = "active";
        $data['details'] = $this->user->getUserByField(Auth::user()->id, 'id');
        return view('admin.user.profile', $data);
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
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'mobile_number' => 'required|numeric|min:10|unique:users,mobile_number',
            'email' => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
            'password' => 'required|min:6|max:15|Same:confirm_password'
        );

        if ($mode == "edit") {
            $rules['email'] = '';
            $rules['password'] = '';
            $rules['mobile_number'] = 'required|numeric|min:10|unique:users,mobile_number,'.$data['id'].',id,deleted_at,NULL';
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $errorRedirectUrl = "admin/user/add";
            if ($mode == "edit") {
                $errorRedirectUrl = "admin/user/edit/" . $data['id'];
            }
            return redirect($errorRedirectUrl)->withInput()->withErrors($validator);
        }
        return false;
    }

    /**
     * Store a newly created user in storage.
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

        $adduser = $this->user->addUser($request->all());
        if ($adduser) {
            Event::fire(new SendMail($adduser));
            $request->session()->flash('alert-success', trans('app.user_add_success'));
            return redirect('admin/user/list');
        } else {
            $request->session()->flash('alert-danger', trans('app.user_error'));
            return redirect('admin/user/add')->withInput();
        }
    }

    /**
     * Update the specified user in storage.
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

        $adduser = $this->user->addUser($request->all());
        if ($adduser) {
            /*
             *  if change_redirect_state  exists then user redirect to user profile
             * */
            if(!empty($request->change_redirect_state) && $request->change_redirect_state == 1){
                $request->session()->flash('alert-success', trans('app.user_profile_update_success'));
                return redirect('admin/user/profile');
            }
            $request->session()->flash('alert-success', trans('app.user_edit_success'));
            return redirect('admin/user/list');
        } else {
            $request->session()->flash('alert-danger', trans('app.user_error'));
            return redirect('admin/user/edit/' . $request->get('id'))->withInput();
        }
    }

    /**
     * Update status to the specified user in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(request $request)
    {
        if(Auth::user()->role_id != 1){
            return back();
        }
        $updateUser = $this->user->updateStatus($request->all());
        if ($updateUser) {
            $request->session()->flash('alert-success', trans('app.user_status_success'));
        } else {
            $request->session()->flash('alert-danger', trans('app.user_error'));
        }
        echo 1;
    }

    /**
     * Delete the specified user in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function delete(request $request)
    {
        if(Auth::user()->role_id != 1){
            return back();
        }
        $deleteUser = $this->user->deleteUser($request->id);
        if ($deleteUser) {
            $request->session()->flash('alert-success', trans('app.user_delete_success'));
        } else {
            $request->session()->flash('alert-danger', trans('app.user_error'));
        }
        echo 1;
    }

    /**
     * Display change password form.
     *
     * @return \Illuminate\Http\Response
     */
    public function changePassword()
    {
        $data['changePasswordTab'] = "active";
        return view('admin.user.change_password', $data);
    }


    /**
     * Update Password of logged in user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(request $request)
    {

        $rules = array(
            'old_password' => 'required|min:6|Different:new_password|max:15',
            'new_password' => 'required|min:6|Same:confirm_password|max:15',
            'confirm_password' => 'required|min:6|max:15'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect('admin/change-password')
                ->withErrors($validator)
                ->withInput($request->except('password'));
        }

        if (Hash::check($request->old_password, Auth::user()->password)) {
            $updateUser = $this->user->updateChangePassword($request->all());
        } else {
            $request->session()->flash('alert-danger', trans('app.old_password_error'));
            return redirect('admin/change-password');
        }


        if ($updateUser) {
            $request->session()->flash('alert-success', trans('app.user_password_success'));
            return redirect('admin/change-password');
        } else {
            $request->session()->flash('alert-danger', trans('app.user_error'));
            return redirect('admin/change-password');
        }
    }

}
