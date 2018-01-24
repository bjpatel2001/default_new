<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\QuotationMapping;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    protected $table = 'tbl_quotation';
    protected $primaryKey = 'id';
    use SoftDeletes;

    public function QuotationMapping(){
        return $this->hasMany('App\Models\QuotationMapping','quotation_id','id');
    }


    /**
     * Get all Quotations getCollection
     *
     * @param array $models
     * @return mixed
     */
    public function getCollection(array $models = [])
    {
        $quotation = Quotation::select('tbl_quotation.*');
        if(isset($models['check_status']) && $models['check_status'] == 1){
            $quotation->where('status',1);
        }
        return $quotation->with('QuotationMapping')->get();
    }

    /**
     * Get all Quotation with quotation & User relationship
     *
     * @return mixed
     */
    public function getDatatableCollection()
    {
        return Quotation::select('tbl_quotation.*');
    }

    /**
     * Query to get quotation total count
     *
     * @param $dbObject
     * @return integer $quotationCount
     */
    public static function getQuotationCount($dbObject)
    {
        $quotationCount = $dbObject->count();
        return $quotationCount;
    }

    /**
     * Scope a query to get all data
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetQuotationData($query, $request)
    {
        return $query->skip($request->start)->take($request->length)->get();
    }

    /**
     * Scope a query to sort data
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSortQuotationData($query, $request)
    {

        return $query->orderBy(config('constant.quotationDataTableFieldArray')[$request->order['0']['column']], $request->order['0']['dir']);

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
     * Add & update Quotation addQuotation
     *
     * @param array $models
     * @return boolean true | false
     */
    public function addQuotation(array $models = [])
    {

        if (isset($models['id'])) {
            $quotation = Quotation::find($models['id']);
        } else {
            $quotation = new Quotation;
        }

        $quotation->section_name = $models['section_name'];
        $quotation->created_by = $quotation->updated_by = Auth::id();
        $quotationId = $quotation->save();
        $i=0;
        foreach ($models['product_name'] as $key=>$product){
            foreach ($product['machine_ids'] as $key1=>$machine){
                if (isset($models['quotation_mapping'][$i])) {
                    $quotationMapping = QuotationMapping::find($models['quotation_mapping'][$i]);
                } else {
                    $quotationMapping = new QuotationMapping;
                }
                $quotationMapping->quotation_id = $quotation->id;
                $quotationMapping->category_id = $key;
                $quotationMapping->machine_id = $machine;
                $quotationMapping->created_by = $quotationMapping->updated_by = Auth::id();
                $quotationMapping->save();
                $i++;
            }
        }
        foreach ($models['unchecked_machine'] as $unchecked){
            QuotationMapping::where('machine_id', $unchecked)->delete();
        }
        if(isset($models['remove_category_ids']) && !empty($models['remove_category_ids'])){
            QuotationMapping::where('category_id',explode(",",$models['remove_category_ids']))->delete();
        }
        if ($quotationId)
            return true;
        else
            return false;
    }

    /**
     * get Quotation By fieldname getQuotationByField
     *
     * @param mixed $id
     * @param string $field_name
     * @return mixed
     */
    public function getQuotationByField($id, $field_name)
    {
        return Quotation::where($field_name, $id)->first();
    }

}
