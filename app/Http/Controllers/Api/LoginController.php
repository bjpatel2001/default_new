<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\AppUser;
use Illuminate\Support\Facades\Password;
use Validator;
use Auth;
use Hash;
use App\Models\AppUserDevice;

class LoginController extends Controller
{

	 use AuthenticatesUsers;

	/**
     * Override login athentication method.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */

    protected $app_user;
    protected $app_users_device;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(AppUser $app_user,AppUserDevice $app_users_device)
    {
        $this->app_user = $app_user;
        $this->app_users_device = $app_users_device;
    }

    public function login(Request $request)
    {

        if (!$request->isMethod('post')) {
            return response(["statusCode" => "0","message" =>["404 Not found."] ,"errors" => []], 404)->header('Content-Type', "json");
        }

        // For Social Login(Facebook)

        if($request->login_type == 2 || $request->login_type == 3){

            $validator = Validator::make($request->all(), [
                'social_login_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response(['statusCode' =>0,'errors' => [],'message' =>$validator->errors()->all() ]);
            }

            $userData = $this->app_user->getAppUserByField($request->social_login_id,'social_login_id');

            if(!empty($userData)){
                $data['notification_id'] = $request->notification_id;
                $data['device_token'] = $request->device_token;
                $data['app_user_id'] = $userData->id;
                $data['device_type'] = $request->device_type;
                $userLoginData =  $this->app_users_device->addAppUserDeviceSocialLogin($data);

                return response(['statusCode' =>1,'data' => $userLoginData,'message' => ['Login Successfully...']]);
            }else{

                $adduser = $this->app_user->addAppUser($request->all());
                $data['notification_id'] = $request->notification_id;
                $data['device_token'] = $request->device_token;
                $data['app_user_id'] = $adduser->id;
                $data['device_type'] = $request->device_type;
                $userData = $this->app_users_device->addAppUserDeviceSocialLogin($data);
                return response(['statusCode' =>1,'data' => $userData,'message' => ['Login Successfully...']]);
            }

        } else if($request->login_type == 4){  // For Web Login
            // For Chcecking wheteher user is blocked or not
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);

        }else{ // For Other Login

            // For Chcecking wheteher user is blocked or not
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
                'device_token' => 'required',
                'notification_id' => 'required',
                'device_type' => 'required',
                'login_type' => 'required',
            ]);
        }

	    if ($validator->fails()) {
            return response(['statusCode' =>0,'errors' => [],'message' =>$validator->errors()->all() ]);
	    }

        if(Auth::guard('app_users')->attempt(['email' => $request->email, 'password' => $request->password, 'status' => 1]))
        {
            $userData = $this->app_users_device->addAppUserDevice($request->all());

            if($userData){
                // Set Profile picture path
                $filepath = url('/') . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR;
                $userData->profile_image = $filepath.$userData->profile_image;
                return response(['statusCode' =>1,'data' => $userData,'message' => ['Login Successfully...']]);
            }
        }else{
            $userData = AppUser::where('email',$request->email);
            if($userData->count() == 0){
                return response(['statusCode' =>0,'message' =>["Your email don't match. Please try again or use a different email to register."]]);
            }else{
                $checkStatus = $userData->where('status',1);
                if($checkStatus->count() == 0){
                    return response(['statusCode' =>2,'message' =>["Your account is suspended temporarily, Please contact Admin.!"]]);
                }
                return response(['statusCode' =>0,'message' =>["Your password don't match. Please try again.!"]]);
            }
        }

    }

    /**
     * Update Password of logged in user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {

        $rules = ['old_password' => 'required|min:6|Different:new_password|max:15',
                    'new_password' => 'required|min:6|Same:confirm_password|max:15',
                    'confirm_password' => 'required|min:6|max:15'
                ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response(['statusCode' =>0,'errors' => [],'message' =>$validator->errors()->all()]);
        }

        $response =  $this->app_user->checkStatus('id',$request->user_id);
        if(!$response){
            return response(['statusCode' =>2,'errors' => [],'message' =>["Your account is suspended temporarily, Please contact Admin.!"] ]);
        }

        $user = AppUser::find($request->user_id);

        if (Hash::check($request->old_password, $user->password)) {

        	$updateUser = $this->app_user->updateChangePassword($request->all());

        	if ($updateUser) {
	            return response(['statusCode' =>1,'message' =>['Your password changed successfully.']]);
	        }
            return response(['statusCode' =>0,'message' =>['Your password changed not successfully please try again.']]);
        }
        return response(['statusCode' =>0,'message' =>['Sorry, your old password does not match. Please enter correct password and try again.'] ]);
        
     }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $rules = ['email' => 'required|email'];

    	$validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response(['statusCode' =>0,'errors' => [],'message' =>$validator->errors()->all() ]);
        }
        
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        if ($response === Password::RESET_LINK_SENT) {
            return response(['statusCode' =>1,'message' =>['A link is sent on your email id. You will be able to create a new password using that link.'] ]);
        }

        return response(['statusCode' =>0,'message' =>['System could not find this email id. Please enter correct email id and try again.'] ]);
    }

    public function afterResetPassword()
    {
        return view('success');
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function signUp(Request $request)
    {
        $rules = [
            'first_name' => 'required|alpha|max:46',
            'last_name' => 'required|alpha|max:46',
            'mobile_number' => 'required|numeric|min:10|unique:users,mobile_number',
            'email' => 'required|email|unique:app_users,email|max:256',
            'business_name' => 'required',
            'country_id' => 'required',
            'state_id' => 'required',
            'password' => 'required|min:8|max:24|Same:confirm_password'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response(['statusCode' =>0,'errors' => [],'message' =>$validator->errors()->all() ]);
        }

        $adduser = $this->app_user->addAppUser($request->all());
        if($adduser){
            return response(['statusCode' =>1,'message' => ['User Created successfully.']]);
        }
        return response(['statusCode' =>0,'message' => ['Problem with adding new User!.']]);

    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker('app_users');
    }

    /**
     * Logout Function.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $rules = [
            'user_id' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response(['statusCode' =>0,'errors' => [],'message' =>$validator->errors()->all()]);
        }

        $updateUser = ["login_status"=>0,"api_token"=>""];
        AppUser::where('id',$request->user_id)->update($updateUser);
        return response(['statusCode' =>1,'error'=> [],'message' => ['User Logout Successfully.']]);
    }

    /**
     * Update User profile.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        $rules = [
            'first_name' => 'required|regex:/^[\pL\s\-]+$/u|max:46',
            'last_name' => 'required|regex:/^[\pL\s\-]+$/u|max:46',
            'mobile_number' => 'required|numeric|min:10|unique:users,mobile_number,'.$request->user_id.',id',
            'business_name' => 'required',
            'user_id' => 'required',

        ];

        $validator = Validator::make($request->all(), $rules);


        if ($validator->fails()) {
            return response(['statusCode' =>0,'errors' => [],'message' =>$validator->errors()->all() ]);
        }
        $adduser = $this->app_user->updateAppUser($request->all());
        if($adduser){
            $userData =  $this->app_user->getUserProfileData($request->all());
            if($userData){
                $filepath = url('/') . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR;
                $userData->profile_image = $filepath.$userData->profile_image;
            }
            return response(['statusCode' =>1,'data' =>$userData,'message' => ['Profile Updated successfully.']]);
        }
        return response(['statusCode' =>0,'message' => ['Problem with updating  details!.']]);

    }

    /**
     * Get User Data from id
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getUserProfileById(Request $request)
    {
        $rules = [
            'user_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response(['statusCode' =>0,'errors' => [],'message' =>$validator->errors()->all() ]);
        }

        $userData =  $this->app_user->getUserProfileData($request->all());
        if($userData){
            $filepath = url('/') . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR;
            $userData->profile_image = $filepath.$userData->profile_image;
            return response(['statusCode' =>1,'data' => $userData,'message' => ['Data Retrived']]);
        }else{
            return response(['statusCode' =>0,'errors' => [],'message' =>["Error retrieving Information"] ]);
        }

    }


}
