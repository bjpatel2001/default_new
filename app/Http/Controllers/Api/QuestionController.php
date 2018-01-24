<?php

namespace App\Http\Controllers\Api;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use App\Models\Question;

class QuestionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $question;
    protected $country;

    public function __construct(Question $question,Country $country)
    {
        $this->question = $question;
        $this->country = $country;
    }

    /**
     * Show the form for creating a new question.
     *
     * @return \Illuminate\Http\Response
     */
    public function getQuestionList()
    {
        $locationData =  $this->country->getLocation();
        $questionData = $this->question->getQuestionCollections();

        if($questionData){
            return response(['statusCode' =>1,'questionData' =>$questionData,'locationData'=>$locationData,'message' => ['Question List Retrieved']]);
        }else{

            return response(['statusCode' =>0,'message' => ['No Record Foung found']]);
        }

    }

}
