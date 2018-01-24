<?php

namespace App\Helpers;

use File;
use Storage;
use Image;

class LaraHelpers
{

    /*
     *  upload file
     *
     *  @param string $filepath
     *  @param object/array $image_name
     *  @param mixed $unlink_image
     *  @param boolean $resize
     *  @return string $filename or $unlink_image
     * */
    public static function upload_image($filepath, $image_name, $unlink_image = '', $resize = false) {

        if($resize == true){
            $final_filepath = $filepath . 'thumb'. DIRECTORY_SEPARATOR;
        }else{
            $final_filepath = $filepath;
        }

        if (!is_dir($final_filepath)) {
            if(env('FILE_STORAGE') == 'Storage'){
                mkdir($final_filepath,0777,true);
            }else{
                File::makeDirectory($final_filepath, 755, true,true);
            }
        }

        /*if($resize == true){
            $thumbPath = $filepath . 'thumb'. DIRECTORY_SEPARATOR;
            if (!is_dir($thumbPath)) {
                if(env('FILE_STORAGE') == 'Storage'){
                    Storage::makeDirectory($thumbPath, 777, true,true);
                }else{
                    File::makeDirectory($thumbPath, 777, true,true);
                }
            }
        }*/

        if ($image_name != "") {
            $file = $image_name;
            $extension = "";
            $extension = '.' . $file->getClientOriginalExtension();
            $file_name = time();
            $filename = $file_name.$extension;

            if($resize == true){
                $thumbPath = $filepath . 'thumb'. DIRECTORY_SEPARATOR;
                $img = Image::make($file->getRealPath());
                $img->resize(240, 180);
                $img->save($thumbPath.$filename);
            }

            $size = $file->getClientSize();

            $publicPath = $filepath;
            $file->move($publicPath, $filename);

            if (isset($unlink_image) && $unlink_image != "") {
                if($resize == true){
                    if(file_exists($thumbPath . $unlink_image)){
                        unlink($thumbPath . $unlink_image);
                    }
                }
                if(file_exists($filepath . $unlink_image)){
                    unlink($filepath . $unlink_image);
                }
            }
            return $filename;
        }
        return $unlink_image;
    }

}