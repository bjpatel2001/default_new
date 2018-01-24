<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\LaraHelpers;
use App\Models\Notification;
use App\Models\NotificationMessage;

class News extends Model
{
    protected $table = 'tbl_news';
    protected $primaryKey = 'id';
    use SoftDeletes;


    public function Category()
    {
        return $this->hasOne('App\Models\Category','id','category_id');
    }

    public function Machine()
    {
        return $this->hasOne('App\Models\Machine','id','machine_id');
    }

    /**
     * Get all News getCollection
     *
     * @param array $models
     * @return mixed
     */
    public function getCollection(array $models = [])
    {
        $news = News::select('tbl_news.*');
        if(isset($models['check_status']) && $models['check_status'] == 1){
            $news->where('status',1);
        }
        return $news->orderBy('id','desc')->get();
    }

    /**
     * Get all News with News & User relationship
     *
     * @return mixed
     */
    public function getDatatableCollection()
    {
        return News::select('tbl_news.*');

    }

    /**
     * Query to get News total count
     *
     * @param $dbObject
     * @return integer $newsCount
     */
    public static function getNewsCount($dbObject)
    {
        $newsCount = $dbObject->count();
        return $newsCount;
    }

    /**
     * Scope a query to get all data
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetNewsData($query, $request)
    {
        return $query->skip($request->start)->take($request->length)->get();
    }

    /*
     *    scopeGetFilteredData from App/Models/News
     *   get filterred Newss
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

        });

    }

    /**
     * Scope a query to sort data
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSortNewsData($query, $request)
    {

        return $query->orderBy(config('constant.newsDataTableFieldArray')[$request->order['0']['column']], $request->order['0']['dir']);

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
     * Add & update News addNews
     *
     * @param array $models
     * @return boolean true | false
     */
    public function addNews(array $models = [])
    {
        $newsLog = new NewsLog();
        if (isset($models['id'])) {
            $news = News::find($models['id']);
            $newsLog->action = "Update";
        } else {
            $news = new News;
            $newsLog->created_by = $news->created_by = Auth::id();
            $newsLog->action = "Add";
        }

        $newsLog->title = $news->title = $models['title'];
        $newsLog->description = $news->description = $models['description'];

        if (isset($models['image']) && $models['image'] != "") {
            $filepath = public_path() . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'news' . DIRECTORY_SEPARATOR;
            $news->image = LaraHelpers::upload_image($filepath, $models['image'], $models['old_image']);
        }
        $newsLog->created_by = $newsLog->updated_by = $news->updated_by = Auth::id();
        if (isset($models['status'])) {
            $newsLog->status = $news->status = $models['status'];
        } else {
            $newsLog->status = $news->status = 0;
        }

        $newsId = $news->save();
        $newsLog->news_id = $news->id;
        $newsLog->save();


        $filepath = public_path() . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'news' . DIRECTORY_SEPARATOR;
        $addmesage = new NotificationMessage();
        $dat=array();
        $dat['title'] = $models['title'];
        $dat['description'] = $models['description'];
        $dat['image'] = url('img/news/'.$news->image);
        $dat['sender_id'] = Auth::id();
        $addmesage->addMessage($dat);

        // For sending the Notification to user who are logged in
        /*
         * @param array $data
         *
         * @param string $type
         * */

        /*Implement in cron*/

           /* if(!isset($models['id'])){
                $data = $news->getNewsByField($news->id,'id');
                $filepath = url('/') . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'news' . DIRECTORY_SEPARATOR;
                $data['title'] = $data->title;
                $data['message'] = $data->description;
                $data['imageUrl'] = $filepath.$data['image'];
                $notification = new Notification();
                $notification->sendNotification($data ,"News");
            }*/

        if ($newsId)
            return true;
        else
            return false;
    }

    /**
     * get News By fieldname getNewsByField
     *
     * @param mixed $id
     * @param string $field_name
     * @return mixed
     */
    public function getNewsByField($id, $field_name)
    {
        return News::where($field_name, $id)->first();
    }

    /**
     * update News Status
     *
     * @param array $models
     * @return boolean true | false
     */
    public function updateStatus(array $models = [])
    {
        $newsLog = new NewsLog();

        $news = News::find($models['id']);
        $newsLog->status = $news->status = $models['status'];
        $newsLog->created_by = $newsLog->updated_by = $news->updated_by = Auth::id();
        $newsLog->action = "Status Changed";
        $newsId = $news->save();
        $newsLog->news_id = $news->id;
        $newsLog->save();


        if ($newsId)
            return true;
        else
            return false;

    }

    /**
     * Delete News
     *
     * @param int $id
     * @return boolean true | false
     */
    public function deleteNews($id)
    {

        $newsLog = new NewsLog();
        $newsLog->created_by = $newsLog->updated_by = Auth::id();
        $newsLog->action = "Delete";
        $delete = News::where('id', $id)->delete();
        $newsLog->news_id = $id;
        $newsLog->save();

        if ($delete)
            return true;
        else
            return false;

    }

    /**
     * Get all News getNewsCollections For API call
     *
     * @param array $models
     * @return mixed
     */
    public function getNewsCollections()
    {
        return News::where('status',1)
            ->orderBy('id','desc')
            ->get();
    }

}
