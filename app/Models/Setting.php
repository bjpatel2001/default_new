<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Helpers\LaraHelpers;

class Setting extends Model
{
    protected $table = 'tbl_settings';
    protected $primaryKey = 'id';


    /**
     * Get all Settings getCollection
     *
     * @return mixed
     */
    public function getCollection()
    {
        return Setting::get();
    }

    /**
     * Add & update Setting addSetting
     *
     * @param array $models
     * @return boolean true | false
     */
    public function addSetting(array $models = [])
    {
        if (isset($models['id'])) {
            $setting = Setting::find($models['id']);
        } else {
            $setting = new Setting;
        }
        $setting->name = "Client Pdf";
        if (isset($models['file_name']) && $models['file_name'] != "") {
            $filepath = public_path() . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR . 'client_pdf' . DIRECTORY_SEPARATOR;
            $setting->file_name = LaraHelpers::upload_image($filepath, $models['file_name'], $models['old_file_name']);
        }
        $setting->status = 1;
        $settingId = $setting->save();

        if ($settingId)
            return true;
        else
            return false;
    }

    /**
     * get Setting By fieldname getSettingByField
     *
     * @param mixed $id
     * @param string $field_name
     * @return mixed
     */
    public function getSettingByField($id, $field_name)
    {
        return Setting::where($field_name, $id)->first();
    }

}
