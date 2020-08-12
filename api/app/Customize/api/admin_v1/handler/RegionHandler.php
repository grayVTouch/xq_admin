<?php


namespace App\Customize\api\admin_v1\handler;


use App\Customize\api\admin_v1\model\RegionModel;
use stdClass;
use function core\convert_obj;

class RegionHandler extends Handler
{
    public static function handle(?RegionModel $model , bool $deep = true): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_obj($model);

        if ($deep) {
            $region = $res->p_id ? RegionModel::find($res->p_id) : null;
            $region = self::handle($region , false);
        } else {
            $region = null;
        }
        $res->region = $region;
        return $res;
    }

}
