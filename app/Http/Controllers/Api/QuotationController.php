<?php

namespace App\Http\Controllers\Api;
use App\Models\Machine;
use App\Models\QuotationMapping;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use App\Models\State;
use App\Models\Country;
use App\Models\Quotation;

class QuotationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $state;
    protected $country;
    protected $quotation;
    protected $quotation_mapping;
    protected $machine;

    public function __construct(State $state,Country $country, Quotation $quotation,QuotationMapping $quotation_mapping,Machine $machine)
    {
        $this->state = $state;
        $this->country = $country;
        $this->quotation = $quotation;
        $this->machine = $machine;
        $this->quotation_mapping = $quotation_mapping;
    }

    /**
     * Get all the Qoutation Data
     * @return \Illuminate\Http\Response
     */
    public function getQuotationData()
    {
        $QuoationData =  $this->quotation_mapping->getQuotationCategory();

        if($QuoationData){
            foreach ($QuoationData as $quoatation_key => $quoatation_val){
                $QuoationData[$quoatation_key]->category_data = $quoatation_val->Category;

                $machineData = $this->machine->getMachines('id',explode(",",$quoatation_val->machine_ids));

                foreach ($machineData as $machine){
                    if(!empty($machine->MachineImage)){
                        foreach($machine->MachineImage as $image_key => $image_val){
                            $filepath = url('/') . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'machine' . DIRECTORY_SEPARATOR . $machine->id . DIRECTORY_SEPARATOR;
                            $machine->MachineImage[$image_key]->image_full_path = $filepath.$image_val->image;
                        }
                    }
                }
                $QuoationData[$quoatation_key]->category_data->machine = $machineData;
            }
            return response(['statusCode' =>1,'data' => $QuoationData,'message' => ['Quotation List Retrieved']]);
        }else{
            return response(['statusCode' =>0,'data' => $QuoationData,'message' => ['No Record Found']]);
        }

     }

}
