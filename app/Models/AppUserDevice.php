<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Models\AppUser;

class AppUserDevice extends Model
{
    protected $table = 'app_users_device';
    protected $primaryKey = 'id';
    public $timestamps = false;

    /**
     * Add & update AppUserDevice addAppUserDevice
     *
     * @param array $models
     * @return boolean true | false
     */

    public function addAppUserDevice(array $models = [])
    {

        if($models['login_type'] != 4){
            $userDeviceCheck = AppUserDevice::where('app_user_id',Auth::guard('app_users')->user()->id);

            if($userDeviceCheck->count() > 0){
                $userDeviceCheck = $userDeviceCheck->first();
                $userDeviceCheck->device_type = $models['device_type'];
                $userDeviceCheck->device_token = $models['device_token'];
                $userDeviceCheck->notification_id = $models['notification_id'];
                $app_users_deviceId = $userDeviceCheck->save();
            }else{
                $app_users_device = new AppUserDevice;
                $app_users_device->app_user_id = Auth::guard('app_users')->user()->id;
                $app_users_device->device_token = $models['device_token'];
                $app_users_device->notification_id = $models['notification_id'];
                $app_users_device->device_type = $models['device_type'];
                $app_users_deviceId = $app_users_device->save();
            }
        }else{
            $app_users_deviceId = true;
        }

        $api_token = str_random(60);
        $updateUser = ["api_token"=>$api_token,"login_status"=>1];
        AppUser::where('id',Auth::guard('app_users')->user()->id)->update($updateUser);
        Auth::guard('app_users')->user()->api_token = $api_token;

        if ($app_users_deviceId)
            return Auth::guard('app_users')->user();
        else
            return false;
    }

    /**
     * Add & update AppUserDevice addAppUserDevice
     *
     * @param array $models
     * @return boolean true | false
     */
    public function addAppUserDeviceSocialLogin(array $models = [])
    {
        $userDeviceCheck = AppUserDevice::where('app_user_id',$models['app_user_id']);

        if($userDeviceCheck->count() > 0){
            $userDeviceCheck = $userDeviceCheck->first();
            $userDeviceCheck->device_type = $models['device_type'];
            $userDeviceCheck->device_token = $models['device_token'];
            $userDeviceCheck->notification_id = $models['notification_id'];
            $app_users_deviceId = $userDeviceCheck->save();
        }else{
            $app_users_device = new AppUserDevice;
            $app_users_device->app_user_id = $models['app_user_id'];
            $app_users_device->device_token = $models['device_token'];
            $app_users_device->notification_id = $models['notification_id'];
            $app_users_device->device_type = $models['device_type'];
            $app_users_deviceId = $app_users_device->save();
        }

        $api_token = str_random(60);
        $updateUser = ["api_token"=>$api_token,"login_status"=>1];
        AppUser::where('id',$models['app_user_id'])->update($updateUser);
        //Auth::guard('app_users')->user()->api_token = $api_token;

        if ($app_users_deviceId)
            return AppUser::find($models['app_user_id']);
        else
            return false;
    }

    /**
     * Update Device Token
     *
     * @param $id
     * @return boolean true | false
     */
        public function UpdataDeviceToken(array $models = []){
            $userDeviceCheck = AppUserDevice::where('app_user_id',$models['app_user_id']);
            if($userDeviceCheck->count() > 0) {
                $userDeviceCheck = $userDeviceCheck->first();
                $userDeviceCheck->device_type = $models['device_type'];
                $userDeviceCheck->device_token = $models['device_token'];
                $userDeviceCheck->notification_id = $models['notification_id'];
               return $app_users_deviceId = $userDeviceCheck->save();
            }

        }

}
