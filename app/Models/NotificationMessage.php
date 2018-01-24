<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationMessage extends Model
{
    protected $table = 'tbl_notification_msg';
    protected $primaryKey = 'id';
    protected $fillable = [
        'message', 'is_sent',
    ];


    public function addMessage($dat)
    {
            $message = new NotificationMessage();
            $message->message = $dat['title'];
            $message->description = $dat['description'];
            $message->image = $dat['image'];
            $message->sender_id = $dat['sender_id'];
            $message->save();
    }

}
