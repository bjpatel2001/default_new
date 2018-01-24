<?php

namespace App\Http\Controllers\Admin;

use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\AppUser;
use Event;
use Hash;
use App\Events\SendMail;
use Illuminate\Config;


class AppUserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $app_user;
    protected $country;
    protected $state;


    public function __construct(AppUser $app_user,Country $country,State $state)
    {
        $this->middleware('auth');
        $this->app_user = $app_user;
        $this->country = $country;
        $this->state = $state;

    }

    /**
     * Display a listing of the user.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {

        /*
         *  getCollection from App/Models/AppUser
         *
         *  @return mixed
         * */

        $data['app_userTab'] = "active";
        $data['masterManagementTab'] = "active open";
        $data['countryData'] = $this->country->getCollection();
        return view('admin.app_user.app_userlist', $data);
    }

    public function datatable(Request $request)
    {
        // default count of user $userCount

        $userCount = 0;

        /*
         *    getDatatableCollection from App/Models/AppUser
         *   get all users
         *
         *  @return mixed
         * */
        $userData = $this->app_user->getDatatableCollection();

        /*
         *    scopeGetFilteredData from App/Models/AppUser
         *   get filterred users
         *
         *  @return mixed
         * */
        $userData = $userData->GetFilteredData($request);

        /*
         *    getUserCount from App/Models/AppUser
         *   get count of users
         *
         *  @return integer
         * */
        $userCount = $this->app_user->getAppUserCount($userData);

        //  Sorting user data base on requested sort order
        if (isset(config('constant.app_userDataTableFieldArray')[$request->order['0']['column']])) {
            $userData = $userData->SortAppUserData($request);
        } else {
            $userData = $userData->SortDefaultDataByRaw('app_users.id', 'desc');
        }

        /*
         *  get paginated collection of user
         *
         * @param  \Illuminate\Http\Request $request
         * @return mixed
         * */
        $userData = $userData->GetAppUserData($request);

        $appData = array();
        $user_type = ["--","Existing User","New User"];
        foreach ($userData as $userData) {
            $row = array();
            $row[] = $userData->name;
            $row[] = $userData->email;
            $row[] = $userData->mobile_number;
            $row[] = (!empty($userData->Country))?$userData->Country->name:"--";
            $row[] = (!empty($userData->State))?$userData->State->name:"--";
            $row[] = $user_type[$userData->user_type];
            $row[] = count($userData->QuotationRequest);
            $row[] = view('datatable.switch', ['module' => "app_user",'status' => $userData->status, 'id' => $userData->id])->render();
            $row[] = view('datatable.action', ['module' => "app_user",'id' => $userData->id])->render();
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
        $data['app_userTab'] = "active";
        $data['masterManagementTab'] = "active open";
        $data['countryData'] = $this->country->getCollection(['check_status' => '1']);
        return view('admin.app_user.add', $data);
    }

    /**
     * Display the specified user.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        /*
         *  get details of the specified user. from App/Models/User
         *
         * @param mixed $id
         * @param string (id) fieldname
         *  @return mixed
         * */
        $data['details'] = $this->app_user->getAppUserByField($id, 'id');
        $data['countryData'] = $this->country->getCollection();
        $data['stateData'] = $this->state->get_state_list(["country_id"=>$data['details']->country_id]);
        $data['app_userTab'] = "active";
        $data['masterManagementTab'] = "active open";
        return view('admin.app_user.edit', $data);
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
            'mobile_number' => 'required|numeric|min:10',
            'email' => 'required|email|unique:app_users,email',
            'password' => 'required|min:6|max:15|Same:confirm_password'
        );

        if ($mode == "edit") {
            $rules['email'] = '';
            $rules['password'] = '';
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            $errorRedirectUrl = "admin/app_user/add";
            if ($mode == "edit") {
                $errorRedirectUrl = "admin/app_user/edit/" . $data['id'];
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

        if(!$request->has('user_type')){
            $request->user_type = 0;
        }

        $adduser = $this->app_user->addAppUser($request->all());
        if ($adduser) {
            Event::fire(new SendMail($adduser));
            $request->session()->flash('alert-success', trans('app.user_add_success'));
            return redirect('admin/app_user/list');
        } else {
            $request->session()->flash('alert-danger', trans('app.user_error'));
            return redirect('admin/app_user/add')->withInput();
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

        $adduser = $this->app_user->addAppUser($request->all());
        if ($adduser) {
            $request->session()->flash('alert-success', trans('app.user_edit_success'));
            return redirect('admin/app_user/list');
        } else {
            $request->session()->flash('alert-danger', trans('app.user_error'));
            return redirect('admin/app_user/edit/' . $request->get('id'))->withInput();
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
       /* if(Auth::user()->role_id != 1){
            return back();
        }*/
        $updateUser = $this->app_user->updateStatus($request->all());
        if ($updateUser) {
            $request->session()->flash('alert-success', trans('app.user_status_success'));
        } else {
            $request->session()->flash('alert-danger', trans('app.user_error'));
        }
        echo 1;
    }

    /**
     * Delete the specified app_user in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function delete(request $request)
    {
        /*if(Auth::user()->role_id != 1){
            return back();
        }*/
        $deleteUser = $this->app_user->deleteAppUser($request->id);
        if ($deleteUser) {
            $request->session()->flash('alert-success', trans('app.user_delete_success'));
        } else {
            $request->session()->flash('alert-danger', trans('app.user_error'));
        }
        echo 1;
    }

    public function getStateList(Request $request) {

        $stateData = $this->state->get_state_list($request->all());

        $stateArray = '<option value="">Select State</option>';
        foreach ($stateData as $state) {
            $stateArray.= '<option value="' . $state->id . '">' . $state->name . '</option>';
        }
        echo $stateArray;
        die();
    }
}
