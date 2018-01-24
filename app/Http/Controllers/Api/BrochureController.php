<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use App\Models\Brochure;

class BrochureController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Brochure $brochure)
    {
        $this->brochure = $brochure;
    }

    /**
     * Show the form for creating a new brochure.
     *
     * @return \Illuminate\Http\Response
     */
    public function getBrochureList()
    {

        $brochureData = $this->brochure->getBrochureCollection();

        if($brochureData){
            $filepath = url('/') . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'brochure' . DIRECTORY_SEPARATOR;
            $data = array();
            foreach($brochureData as $row)
            {
                $data = $row->file_name = $filepath.$row->file_name;
            }
            return response(['statusCode' =>1,'data' =>$brochureData,'message' => ['Brochure List Retrieved']]);
        }else{

            return response(['statusCode' =>0,'message' => ['No Brochure found']]);
        }

    }

}
