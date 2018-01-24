<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CategoryLog;
use App\Models\Machine;
use Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    protected $table = 'tbl_category';
    protected $primaryKey = 'id';
    use SoftDeletes;


    public function Machine()
    {
        return $this->hasMany('App\Models\Machine','category_id','id')->where('status',1);
    }

    /**
     * Get all Categorys getCollection
     *
     * @param array $models
     * @return mixed
     */
    public function getCollection(array $models = [])
    {
        $category = Category::with('Machine')->select('tbl_category.*');
        if(isset($models['check_status']) && $models['check_status'] == 1){
            $category->where('status',1);
        }
        return $category->get();
    }

    /**
     * Get all Category with category & User relationship
     *
     * @return mixed
     */
    public function getDatatableCollection()
    {
        return Category::select('tbl_category.*');
    }

    /**
     * Query to get category total count
     *
     * @param $dbObject
     * @return integer $categoryCount
     */
    public static function getCategoryCount($dbObject)
    {
        $categoryCount = $dbObject->count();
        return $categoryCount;
    }

    /**
    * get Machine By category wise
    *
    * @param mixed $id
    *
    * @return response
    */
        public function getProductByCategoryId($id)
        {
            return Machine::where('category_id',$id)->select('machine_name','id')->get()->toArray();
        }

    /**
     * Scope a query to get all data
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetCategoryData($query, $request)
    {
        return $query->skip($request->start)->take($request->length)->get();
    }

    /*
     *    scopeGetFilteredData from App/Models/Category
     *   get filterred categorys
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
    public function scopeSortCategoryData($query, $request)
    {

        return $query->orderBy(config('constant.categoryDataTableFieldArray')[$request->order['0']['column']], $request->order['0']['dir']);

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
     * Add & update Category addCategory
     *
     * @param array $models
     * @return boolean true | false
     */
    public function addCategory(array $models = [])
    {
        $categoryLog = new CategoryLog();
        if (isset($models['id'])) {
            $category = Category::find($models['id']);
            $categoryLog->action = "Update";
        } else {
            $category = new Category;
            $categoryLog->created_by = $category->created_by = Auth::id();
            $categoryLog->action = "Add";
        }

        $categoryLog->category_name = $category->category_name = $models['category_name'];
        $categoryLog->created_by = $categoryLog->updated_by = $category->updated_by = Auth::id();
        if (isset($models['status'])) {
            $categoryLog->status = $category->status = $models['status'];
        } else {
            $categoryLog->status = $category->status = 0;
        }

        $categoryId = $category->save();
        $categoryLog->category_id = $category->id;
        $categoryLog->save();

        if ($categoryId)
            return true;
        else
            return false;
    }

    /**
     * get Category By fieldname getCategoryByField
     *
     * @param mixed $id
     * @param string $field_name
     * @return mixed
     */
    public function getCategoryByField($id, $field_name)
    {
        return Category::where($field_name, $id)->first();
    }

    /**
     * update Category Status
     *
     * @param array $models
     * @return boolean true | false
     */
    public function updateStatus(array $models = [])
    {
        $categoryLog = new CategoryLog();

        $category = Category::find($models['id']);
        $categoryLog->status = $category->status = $models['status'];
        $categoryLog->created_by = $categoryLog->updated_by = $category->updated_by = Auth::id();
        $categoryLog->action = "Change Password";
        $categoryId = $category->save();
        $categoryLog->category_id = $category->id;
        $categoryLog->save();


        if ($categoryId)
            return true;
        else
            return false;

    }

    /**
     * Delete Category
     *
     * @param int $id
     * @return boolean true | false
     */
    public function deleteCategory($id)
    {
        $categoryData = Machine::where('category_id',$id)->first();
        if(count($categoryData)>0){
            return false;
        }
        $categoryLog = new CategoryLog();
        $categoryLog->created_by = $categoryLog->updated_by = Auth::id();
        $categoryLog->action = "Delete";
        $delete = Category::where('id', $id)->delete();
        $categoryLog->category_id = $id;
        $categoryLog->save();

        if ($delete)
            return true;
        else
            return false;

    }

    /**
     * get Machine By category wise
     *
     * @param mixed $id
     *
     * @return response
     */
    public function getMachineByCategory($id)
    {
        return Category::with('Machine')->where('id',$id)->get();
    }

}
