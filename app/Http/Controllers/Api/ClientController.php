<?php

namespace App\Http\Controllers\Api;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use App\Models\Client;

class ClientController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Client $client,Setting $setting)
    {
        $this->client = $client;
        $this->setting = $setting;
    }

    /**
     * Show the form for creating a new client.
     *
     * @return \Illuminate\Http\Response
     */
    public function getClientList()
    {
        $clientData = $this->client->getClientCollections();

        if($clientData){
            $filepath = url('/') . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'client' . DIRECTORY_SEPARATOR;

            foreach($clientData as $key => $row)
            {
                $clientData[$key]->image = $filepath.$row->image;
            }

            $clientPdfData = $this->setting->getSettingByField(1,'id');
            $clientPdfData->full_file_name = url('/') . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'client_pdf' . DIRECTORY_SEPARATOR . $clientPdfData->file_name;

            return response(['statusCode' =>1,'data' =>$clientData,'client_pdf'=>$clientPdfData,'message' => ['Client List Retrieved']]);
        }else{

            return response(['statusCode' =>0,'message' => ['No Client found']]);
        }

    }

}
