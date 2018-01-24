<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\LaraHelpers;
use App\Models\QuestionLog;
use App\Models\QuestionOption;

class Question extends Model
{
    protected $table = 'tbl_question';
    protected $primaryKey = 'id';
    use SoftDeletes;

    /**
     * Get all Question getCollection
     *
     * @param array $models
     * @return mixed
     */
    public function getCollection(array $models = [])
    {
        $question = Question::select('tbl_question.*');
        if(isset($models['check_status']) && $models['check_status'] == 1){
            $question->where('status',1);
        }
        return $question->get();
    }

    /**
     * Get all Question with Question & User relationship
     *
     * @return mixed
     */
    public function getDatatableCollection()
    {
        return Question::select('tbl_question.*');

    }

    /**
     * Query to get Question total count
     *
     * @param $dbObject
     * @return integer $questionCount
     */
    public static function getQuestionCount($dbObject)
    {
        $questionCount = $dbObject->count();
        return $questionCount;
    }

    /**
     * Scope a query to get all data
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetQuestionData($query, $request)
    {
        return $query->skip($request->start)->take($request->length)->get();
    }

    /*
     *    scopeGetFilteredData from App/Models/Question
     *   get filterred Questions
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
    public function scopeSortQuestionData($query, $request)
    {

        return $query->orderBy(config('constant.questionDataTableFieldArray')[$request->order['0']['column']], $request->order['0']['dir']);

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
     * Add & update Question addQuestion
     *
     * @param array $models
     * @return boolean true | false
     */
    public function addQuestion(array $models = [])
    {
        $questionLog = new QuestionLog();
        if (isset($models['id'])) {
            $question = Question::find($models['id']);
            $questionLog->action = "Update";
            /*if($models['type'] == 0){
                $questionOption = new QuestionOption();
                $models['id'];
                $questionOption->updateQuestion($models);
            }else{
                $questionOption = new QuestionOption();
                $questionOption->deleteOption($models['id']);
            }*/

        } else {
            $question = new Question;
            $questionLog->created_by = $question->created_by = Auth::id();
            $questionLog->action = "Add";
        }

        $questionLog->question = $question->question = $models['question'];
        $question->type = $models['type'];
        $questionLog->created_by = $questionLog->updated_by = $question->updated_by = Auth::id();
        if (isset($models['status'])) {
            $questionLog->status = $question->status = $models['status'];
        } else {
            $questionLog->status = $question->status = 0;
        }

        $questionId = $question->save();
        $questionLog->question_id = $question->id;
        $questionLog->save();
            if($models['type'] == 0){
                $questionOption = new QuestionOption();
                if (isset($models['id'])) {
                    $questionOption->deleteOption($models['id']);
                }
                $models['id'] = $question->id;
                $questionOption->addQuestionOption($models);
            }

        if ($questionId){
            return true;
        }else{
            return false;
        }

    }

    /**
     * get Question By fieldname getQuestionByField
     *
     * @param mixed $id
     * @param string $field_name
     * @return mixed
     */
    public function getQuestionByField($id, $field_name)
    {
        return Question::where($field_name, $id)->first();
    }

    /**
     * update Question Status
     *
     * @param array $models
     * @return boolean true | false
     */
    public function updateStatus(array $models = [])
    {
        $questionLog = new QuestionLog();
        $question = Question::find($models['id']);
        $questionLog->status = $question->status = $models['status'];
        $questionLog->created_by = $questionLog->updated_by = $question->updated_by = Auth::id();
        $questionLog->action = "Status Changed";
        $questionId = $question->save();
        $questionLog->question_id = $question->id;
        $questionLog->save();

        if ($questionId)
            return true;
        else
            return false;

    }

    /**
     * Delete Question
     *
     * @param int $id
     * @return boolean true | false
     */
    public function deleteQuestion($id)
    {

        $questionLog = new QuestionLog();
        $questionLog->created_by = $questionLog->updated_by = Auth::id();
        $questionLog->action = "Delete";
        $delete = Question::where('id', $id)->delete();
        $questionLog->question_id = $id;
        $questionLog->save();

        if ($delete)
            return true;
        else
            return false;

    }

    public function Option()
    {
        return $this->hasMany('App\Models\QuestionOption','question_id','id');
    }

    /**
     * Get all Question getNewsCollections For API call
     *
     * @param array $models
     * @return mixed
     */
    public function getQuestionCollections()
    {
        return Question::with('Option')->where('status',1)
            ->get();
    }
}
