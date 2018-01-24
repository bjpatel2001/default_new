<?php

namespace App\Models;
use App\Models\AppUser;
use Illuminate\Database\Eloquent\Model;
use Auth;
use phpDocumentor\Reflection\Types\Boolean;
use PushNotification;


class Notification extends Model
{
    protected $table = 'tbl_notifications';
    protected $primaryKey = 'id';
    protected $fillable = [
        'notification_id', 'receiver_id', 'sender_id','message','created_by','updated_by','type',
    ];



    /**
     * Send Notification to user logged in
     * @param  string $type
     * @param array $data
     * @return Boolean
     */

    public function sendNotification($data,$type){

        $users = new AppUser();
        $loggedUser = $users->getLoggedUser();
        if(isset($data['sender_id']) && !empty($data['sender_id'])):
            $notificationStatus = $this->addNotifications($loggedUser,$type,$data['message'],$data['sender_id']);
        else:
            $notificationStatus = $this->addNotifications($loggedUser,$type,$data['message']);
        endif;
        $notificationIds = array();

        if($loggedUser){
            foreach ($loggedUser as $user){
                $notificationIds[] = PushNotification::Device($user->UserDevice->notification_id);
            }
        }

        $devices = PushNotification::DeviceCollection($notificationIds);
        $message = ["title"=>$data['title'],
                    "is_background"=>false,
                    "message"=>$data['message'],
                    "image" => $data['imageUrl'],
                    "type" => $type,
                    "timestamp"=>date('Y-m-d H:i:s')];
        $collection = PushNotification::app('avivaAndroid')
            ->to($devices)
            ->send($message);
        foreach ($collection->pushManager as $push) {
            $response = $push->getAdapter()->getResponse();
        }
        return true;
    }

    /**
     * Add Notification Log
     * @param string $type
     * @param $data
     * @return boolean true | false
     */
    public function addNotifications($data,$type,$message,$sender_id="")
    {
        if(isset($data)){
            foreach($data as $UserData)
            {
                if(isset($sender_id) && !empty($sender_id)):
                    $notificationData = Notification::create([
                        'type' => $type,
                        'notification_id' => $UserData->UserDevice->notification_id,
                        'receiver_id' =>$UserData->id,
                        'sender_id' => $sender_id,
                        'message' => $message,
                    ]);
                else:
                    $notificationData = Notification::create([
                        'type' => $type,
                        'notification_id' => $UserData->UserDevice->notification_id,
                        'receiver_id' =>$UserData->id,
                        'sender_id' => Auth::id(),
                        'message' => $message,

                    ]);
                endif;
            }

        }
    }

}
