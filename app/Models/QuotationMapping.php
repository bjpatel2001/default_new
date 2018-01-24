<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class QuotationMapping extends Model
{
    //

    protected $table = 'tbl_quotation_category_machine';
    protected $primaryKey = 'id';

    public function Category()
    {
        return $this->hasOne('App\Models\Category','id','category_id');
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quotation_id','category_id','machine_id',
    ];

    public function CreatedBy()
    {
        return $this->hasOne('App\Models\User','id','created_by');
    }

    /**
     * Get all Quotation Mapping log quotation wise getCollection
     *
     * @param int $id
     * @return mixed
     */
    public function getCollection($id)
    {
        return QuotationMapping::with('CreatedBy')->where('quotation_id',$id)->get();
    }

    /**
     * Get all Quotation Category and machine and images for API CAll
     *
     * @return mixed
     */

    public function getQuotationCategory()
    {
        return QuotationMapping::with('Category')->select('tbl_quotation_category_machine.*',\DB::raw("GROUP_CONCAT(tbl_quotation_category_machine.machine_id) as machine_ids"))
                            ->groupBy('category_id')
                            ->get();
    }
}
