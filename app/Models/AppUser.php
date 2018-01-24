<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;
use App\Helpers\LaraHelpers;
use Illuminate\Notifications\Notifiable;
use App\Notifications\AppUserResetPasswordNotification;



class AppUser extends Authenticatable
{

    use Notifiable;
    protected $table = 'app_users';
    protected $primaryKey = 'id';
    protected $guard = "app_users";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'name', 'email', 'password', 'first_name', 'last_name', 'business_name', 'profile_image','social_login_id','login_type',
        'api_response','mobile_number', 'status', 'created_by', 'updated_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //Send password reset notification
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new AppUserResetPasswordNotification($token));
    }

    public function State()
    {
        return $this->hasOne('App\Models\State','id','state_id');
    }

    public function Country()
    {
        return $this->hasOne('App\Models\Country','id','country_id');
    }

    public function QuotationRequest()
    {
        return $this->hasOne('App\Models\RequestQuotation','user_id','id');
    }
    /**
     * Get all User getCollection
     *
     * @return mixed
     */
    public function getCollection()
    {

        return AppUser::get();
    }

    /**
     * Get all AppUser with role and ParentAppUser relationship
     *
     * @return mixed
     */
    public function getDatatableCollection()
    {
        return AppUser::with('QuotationRequest')->select('app_users.*');
    }

    /**
     * Query to get app_user total count
     *
     * @param $dbObject
     * @return integer $userCount
     */
    public static function getAppUserCount($dbObject)
    {
        $userCount = $dbObject->count();
        return $userCount;
    }

    /**
     * Scope a query to get all data
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetAppUserData($query, $request)
    {
        return $query->skip($request->start)->take($request->length)->get(config('constant.app_userFieldArray'));
    }

    /*
     *    scopeGetFilteredData from App/Models/AppUser
     *   get filterred app_users
     *
     *  @param  object $query
     *  @param  \Illuminate\Http\Request $request
     *  @return mixed
     * */
    public function scopeGetFilteredData($query, $request)
    {
        $filter = $request->filter;
        $Datefilter = $request->filterDate;
        $filterSelect = $request->filterSelect;
        /*
         * @param string $filter  text type value
         * @param string $Datefilter  date type value
         * @param string $filterSelect select value
         *
         *  @return mixed
         * */
        return $query->Where(function ($query) use ($filter, $Datefilter, $filterSelect) {
            if (count($filter) > 0) {
                foreach ($filter as $key => $value) {
                    if ($value != "") {
                        $query->where($key, 'LIKE', '%' . $value . '%');
                    }
                }
            }

            if (count($Datefilter) > 0) {
                foreach ($Datefilter as $dtkey => $dtvalue) {
                    if ($dtvalue != "") {
                        $query->where($dtkey, 'LIKE', '%' . date('Y-m-d', strtotime($dtvalue)) . '%');
                    }
                }
            }

            if (count($filterSelect) > 0) {
                foreach ($filterSelect as $Sekey => $Sevalue) {
                    if ($Sevalue != "") {
                        $query->whereRaw('FIND_IN_SET(' . $Sevalue . ',' . $Sekey . ')');
                    }
                }
            }
            $query;

        });

    }

    /**
     * Scope a query to sort data
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSortAppUserData($query, $request)
    {

        return $query->orderBy(config('constant.app_userDataTableFieldArray')[$request->order['0']['column']], $request->order['0']['dir']);

    }

    /**
     * Scope a query to sort data
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string $column
     * @param  string $dir
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSortDefaultDataByRaw($query, $column, $dir)
    {
        return $query->orderBy($column, $dir);
    }

    /**
     * Add & update AppUser addUser
     *
     * @param array $models
     * @return boolean true | false
     */
    public function addAppUser(array $models = [])
    {

        if (isset($models['id'])) {
            $user = AppUser::find($models['id']);
        } else {
            $user = new AppUser;
            if(isset(Auth::user()->id) && Auth::user()->id != null){
                $user->created_by = Auth::user()->id;
            }
            else{
                $user->created_by = 0;
            }
        }

        if(isset($models['password'])){
            $user->password = $models['password'];
        }else{
            $user->password = "";

        }
        if(isset($models['email'])){
            $user->email = $models['email'];
        }else{
            $user->email = "";
        }

        if(isset($models['first_name'])){
            $user->name = $models['first_name'] . " " . $models['last_name'];
            $user->first_name = $models['first_name'];
            $user->last_name = $models['last_name'];
        }else{
            $user->name = "";
            $user->first_name = "";
            $user->last_name = "";
        }

        if(isset($models['mobile_number'])){
            $user->mobile_number = $models['mobile_number'];
        }else{
            $user->mobile_number = "";
        }

        if(isset($models['user_type'])){
            $user->user_type = $models['user_type'];
        }else{
            $user->user_type = 0;
        }

        if(isset($models['login_type'])){
            $user->login_type = $models['login_type'];
        }else{
            $user->login_type = 0;
        }
        if(isset($models['api_response'])){
            $user->api_response = $models['api_response'];
        }
        if(isset($models['business_name'])){
            $user->business_name = $models['business_name'];
        }else{
            $user->business_name = "";
        }
        if(isset($models['country_id'])){
            $user->country_id = $models['country_id'];
        }else{
            $user->country_id = "";
        }
        if(isset($models['state_id'])){
            $user->state_id = $models['state_id'];
        }else{
            $user->state_id = "";
        }
        if(isset($models['social_login_id'])){
            $user->social_login_id = $models['social_login_id'];
        }else{
            $user->social_login_id = "";
        }

        $user->api_token = str_random(60);

        if (isset($models['profile_image']) && $models['profile_image'] != "") {
            $filepath = public_path() . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR;
            if(!isset($models['old_profile_image'])){
                $models['old_profile_image'] = "";
            }
            $user->profile_image = LaraHelpers::upload_image($filepath, $models['profile_image'], $models['old_profile_image']);
        }

        if (isset($models['status'])) {
            $user->status = $models['status'];
        } else {
            $user->status = 0;
        }

        if(isset(Auth::user()->id) && Auth::user()->id != null){
            $user->updated_by = Auth::user()->id;
        }else{
            $user->updated_by = 0;
        }

        $userId = $user->save();

        if ($userId && $models['login_type'] == 1) {
            return $user;
        }else if($userId && $models['login_type'] == 2){
          return $user;
        }else if($userId && $models['login_type'] == 3){
            return $user;
        }else if($userId && $models['login_type'] == 4){
            if (!isset($models['id'])) {
                $user->password = $models['password'];
            }
            $user->subjectLine = "Welcome to Aviva";
            $user->viewTemplate = "admin.emails.user_signup";
            return $user;
        }
        else {
            return false;
        }
    }

    /**
     * get AppUser By fieldname getUserByField
     *
     * @param mixed $id
     * @param string $field_name
     * @return mixed
     */
    public function getAppUserByField($id, $field_name)
    {
        return AppUser::where($field_name, $id)->first();
    }

    /**
     * update User Status
     *
     * @param array $models
     * @return boolean true | false
     */
    public function updateStatus(array $models = [])
    {
        $user = AppUser::find($models['id']);
        $user->status = $models['status'];
        $user->updated_by = Auth::user()->id;
        $user->updated_at = date('Y-m-d H:i:s');
        $userId = $user->save();
        if ($userId)
            return true;
        else
            return false;

    }

    /**
     * update AppUser's Password
     *
     * @param array $models
     * @return boolean true | false
     */
    public function updateChangePassword(array $models = [])
    {
        $user = AppUser::find($models['user_id']);
        $user->password = bcrypt($models['new_password']);
        $user->updated_by = $models['user_id'];
        $user->updated_at = date('Y-m-d H:i:s');
        $userId = $user->save();
        if ($userId)
            return true;
        else
            return false;

    }

    /**
     * Delete User
     *
     * @param int $id
     * @return boolean true | false
     */
    public function deleteAppUser($id)
    {
        $delete = AppUser::where('id', $id)->delete();
        if ($delete) {
            $userRequestQuestion = new RequestQuestion();
            $userRequestCategory = new RequestCategory();
            $userRequestQuotation = RequestQuotation::where('user_id', $id)->get();
            if(count($userRequestQuotation) > 0){
                foreach ($userRequestQuotation as $item) {
                    $userRequestQuestion->deleteRequestQuestion('request_id', $item->id);
                    $userRequestCategory->deleteRequestCategory('request_id', $item->id);
                }
                RequestQuotation::where('user_id', $id)->delete();
            }
            return true;
        }
        return false;

    }

    /**
     * Check For User is blocked or not
     *
     * @param string $field_name
     * @param int|string $data
     * @return boolean
     */
    public function checkStatus($field_name,$data)
    {
        $useData = AppUser::where($field_name,$data)->where('status',1)->first();
        if(empty($useData)){
            return false;
        }
        return true;
    }

    /**
     * get AppUser Data by Id
     *
     * @param mixed $id
     *
     * @return mixed
     */
    public function getUserProfileData($data)
    {
        return AppUser::where('id',$data['user_id'])->first();
    }

    /**
     * Update App User profile from Adroid
     *
     * @param array $models
     * @return boolean true | false
     */
    public function updateAppUser(array $models = [])
    {

        if (isset($models['user_id'])) {
            $user = AppUser::find($models['user_id']);
        }

        $user->name = $models['first_name'] . " " . $models['last_name'];
        $user->first_name = $models['first_name'];
        $user->last_name = $models['last_name'];
        $user->mobile_number = $models['mobile_number'];
        $user->business_name = $models['business_name'];
        if(isset($models['state_id'])){
            $user->state_id = $models['state_id'];
        }
        if(empty($user->email)){
            $user->email = $models['email'];
        }
        if(isset($models['country_id'])){
            $user->country_id = $models['country_id'];
        }

        if (isset($models['profile_image']) && $models['profile_image'] != "") {
            $filepath = public_path() . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'user' . DIRECTORY_SEPARATOR;
            if(!isset($models['old_profile_image'])){
                $models['old_profile_image'] = "";
            }
            $user->profile_image = LaraHelpers::upload_image($filepath, $models['profile_image'], $models['old_profile_image']);
        }
        $user->updated_by = $models['user_id'];
        $userId = $user->save();
        if ($userId) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * get Logged In User
     *
     *
     * @return mixed
     */
    public function getLoggedUser()
    {
        return AppUser::with('UserDevice')->where('login_status','=','1')->get();
    }

    public function UserDevice()
    {
        return $this->hasOne('App\Models\AppUserDevice','app_user_id','id');
    }

}
