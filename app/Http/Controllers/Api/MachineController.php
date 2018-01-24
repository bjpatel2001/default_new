<?php

namespace App\Http\Controllers\Api;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use App\Models\Machine;
use App\Models\Category;
use App\Models\MachineImage;

class MachineController extends Controller
{
    protected $client;
    protected $machine;
    protected $category;
    protected $machineimage;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Machine $machine, Category $category,MachineImage $machineimage,Client $client)
    {
        $this->client = $client;
        $this->machine = $machine;
        $this->category = $category;
        $this->machineimage = $machineimage;
    }

    /**
     * Get all Machine for Dashboard For API call.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDashboardMachineList()
    {
        $machineData = $this->machine->getRecentMachine();

        if($machineData){
            foreach ($machineData as $machine){
                if(!empty($machine->MachineImage)){
                    foreach($machine->MachineImage as $image_key => $image_val){
                        $filepath = url('/') . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'machine' . DIRECTORY_SEPARATOR . $machine->id . DIRECTORY_SEPARATOR;
                        $thum_filepath = url('/') . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'machine' . DIRECTORY_SEPARATOR . $machine->id . DIRECTORY_SEPARATOR . 'thumb' . DIRECTORY_SEPARATOR;

                        $machine->MachineImage[$image_key]->image_full_path = $filepath.$image_val->image;
                        $machine->MachineImage[$image_key]->image_thumb_path = $thum_filepath.$image_val->image;
                    }
                }
            }

            $clientData = $this->client->getRecentClientCollection();
            if(count($clientData) > 0){
                $filepath = url('/') . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'client' . DIRECTORY_SEPARATOR;

                foreach($clientData as $key => $row){
                    $clientData[$key]->image = $filepath.$row->image;
                }
            }

            return response(['statusCode' =>1,'data' =>$machineData,'client_data'=>$clientData,'message' => ['Machine and Client List Retrieved']]);
        }else{

            return response(['statusCode' =>0,'message' => ['No Machine found']]);
        }
    }

    /**
     * Get All Category and machine
     *
     * @return \Illuminate\Http\Response
     */
    public function getCategoryWithMachine()
    {
        $categoryData = $this->category->getCollection(["check_status"=>1]);

        if($categoryData){
            return response(['statusCode' =>1,'data' =>$categoryData,'message' => ['Category List Retrived']]);
        }else{
            return response(['statusCode' =>0,'message' => ['No Category found']]);
        }

    }

    /**
     * Get All Machine category wise
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getMachineListing(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['statusCode' =>0,'errors' => [],'message' =>$validator->errors()->all()]);
        }

        $machineData = Machine::with('MachineImage')->where('category_id', $request->category_id)->where('status',1)->get();

        if(count($machineData) > 0){
            foreach ($machineData as $machine){
                if(!empty($machine->MachineImage)){
                    foreach($machine->MachineImage as $image_key => $image_val){
                        $filepath = url('/') . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'machine' . DIRECTORY_SEPARATOR . $machine->id . DIRECTORY_SEPARATOR;
                        $thum_filepath = url('/') . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'machine' . DIRECTORY_SEPARATOR . $machine->id . DIRECTORY_SEPARATOR . 'thumb' . DIRECTORY_SEPARATOR;
                        $machine->MachineImage[$image_key]->image_full_path = $filepath.$image_val->image;
                        $machine->MachineImage[$image_key]->image_thumb_path = $thum_filepath.$image_val->image;
                    }
                }
            }
            return response(['statusCode' =>1,'data' =>$machineData,'message' => ['Machine List Retrieved']]);
        }else{

            return response(['statusCode' =>1, 'data' =>$machineData,'message' => ['No Machine found']]);
        }
    }

    /**
     * Get All Machine Details
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getMachineDetail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'machine_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response(['statusCode' =>0,'errors' => [],'message' =>$validator->errors()->all()]);
        }

        $machine = $this->machine->getMachineByField($request->machine_id,'id');
        $machine->brochure_full_path = "";
        if(!empty($machine->Brochure)){
            $filepath = url('/') . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'brochure' . DIRECTORY_SEPARATOR;
            $machine->brochure_full_path = $filepath.$machine->Brochure->file_name;
        }
        if(!empty($machine->MachineImage)){
            foreach($machine->MachineImage as $image_key => $image_val){
                $filepath = url('/') . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'machine' . DIRECTORY_SEPARATOR . $machine->id . DIRECTORY_SEPARATOR;
                $thum_filepath = url('/') . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'machine' . DIRECTORY_SEPARATOR . $machine->id . DIRECTORY_SEPARATOR . 'thumb' . DIRECTORY_SEPARATOR;
                $machine->MachineImage[$image_key]->image_full_path = $filepath.$image_val->image;
                $machine->MachineImage[$image_key]->image_thumb_path = $thum_filepath.$image_val->image;
            }
        }

        if(!empty($machine)){
            return response(['statusCode' =>1,'data' =>$machine,'message' => ['Machine Detail Retrieved']]);
        }
        return response(['statusCode' =>0,'message' => ['No Machine found']]);
    }
}
