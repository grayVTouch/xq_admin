<?php


namespace App\Customize\api\admin\handler;


use App\Customize\api\admin\model\Model;
use App\Customize\api\admin\model\RegionModel;
use stdClass;
use function core\convert_object;

class RegionHandler extends Handler
{
    public static function handle(?Model $model , array $with = [] , bool $deep = true): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $model = convert_object($model);

        if (in_array('region' , $with)) {
            if ($deep) {
                $region = $model->p_id ? RegionModel::find($model->p_id) : null;
                $region = self::handle($region , $with , false);
            } else {
                $region = null;
            }
            $model->region = $region;

        }

        return $model;
    }

}
