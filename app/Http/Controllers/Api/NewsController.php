<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use App\Models\News;

class NewsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $news;

    public function __construct(News $news)
    {
        $this->news = $news;
    }

    /**
     * Show the form for creating a new news.
     *
     * @return \Illuminate\Http\Response
     */
    public function getNewsList()
    {

        $newsData = $this->news->getNewsCollections();

        if($newsData){
            $filepath = url('/') . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'news' . DIRECTORY_SEPARATOR;
            $data = array();
            foreach($newsData as $row)
            {
                $data = $row->image = $filepath.$row->image;
            }
            return response(['statusCode' =>1,'data' =>$newsData,'message' => ['News List Retrieved']]);
        }else{

            return response(['statusCode' =>0,'message' => ['No News found']]);
        }

    }

}
