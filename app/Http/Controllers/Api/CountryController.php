<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\Country;

class CountryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $country;

    public function __construct(Country $country)
    {
        $this->country = $country;
    }

    /**
     * get the list of country with all states.
     *
     * @return \Illuminate\Http\Response
     */
    public function countryList()
    {
        $countryData = $this->country->getCollection(["check_status"=>1]);

        if(count($countryData) > 0){
            return response(['statusCode' =>1,'data' =>$countryData,'message' => ['Country List with all States Retrieved']]);
        }
        return response(['statusCode' =>0,'message' => ['No Country found']]);
    }

}
