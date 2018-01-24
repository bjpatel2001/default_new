<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MachineImage extends Model
{
    //

    protected $table = 'tbl_machine_images';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'image','machine_id',
    ];

    public function CreatedBy()
    {
        return $this->hasOne('App\Models\User','id','created_by');
    }

    /**
     * Get all Machine log machine wise getCollection
     *
     * @param int $id
     * @return mixed
     */
    public function getCollection($id)
    {
        return MachineImage::with('CreatedBy')->where('machine_id',$id)->get();
    }

    /**
     * Delete Machine Image
     *
     * @param int $id
     * @return boolean true | false
     */
    public function deleteImage($id)
    {
        $delete = MachineImage::where('id', $id);
        $imageData = $delete->first();
        $filepath = public_path() . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'machine' . DIRECTORY_SEPARATOR . $imageData->machine_id . DIRECTORY_SEPARATOR;
        $thumbPath = public_path() . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'machine' . DIRECTORY_SEPARATOR . $imageData->machine_id . DIRECTORY_SEPARATOR . 'thumb'. DIRECTORY_SEPARATOR;

        $unlink_image = $imageData->image;
        if (isset($unlink_image) && $unlink_image != "") {
            if(file_exists($thumbPath . $unlink_image)){
                unlink($thumbPath . $unlink_image);
            }
            if(file_exists($filepath . $unlink_image)){
                unlink($filepath . $unlink_image);
            }
        }
        $delete = $delete->delete();

        if ($delete)
            return true;
        else
            return false;

    }

    /**
     * Get all Machine image by id
     *
     * @param int $id
     * @return mixed
     */
    public function getAllImage($id)
    {
        return MachineImage::where('machine_id',$id)->get();
    }

    /**
     * Get single Machine image by id
     *
     * @param int $id
     * @return mixed
     */
    public function getImage($id)
    {
        return MachineImage::where('machine_id',$id)->first();
    }

}
