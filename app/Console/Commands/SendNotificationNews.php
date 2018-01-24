<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use App\Models\NotificationMessage;
use App\Models\User;
use App\Models\AppUser;
use App\Models\News;
use Illuminate\Database\Eloquent\Model;
use Auth;
use phpDocumentor\Reflection\Types\Boolean;
use PushNotification;

class SendNotificationNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send news Notification to all users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $allNews = NotificationMessage::where('is_sent','0')->get();
        foreach($allNews as $news):

            $data['title'] = $news->message;
            $data['message'] = $news->description;
            $data['imageUrl'] = $news->image;
            $data['sender_id'] = $news->sender_id;
            $notification = new Notification();
            $success = $notification->sendNotification($data ,"News");
            if($success){
                $this->info('successfully send notification!');
                $update = NotificationMessage::find($news->id);
                $update->is_sent = '1';
                $update->save();
            }else{
                $this->info('something went wrong!');
            }
        endforeach;

    }
}
