<?php


namespace App\Customize\api\admin\action;

use App\Customize\api\admin\handler\RegionHandler;
use App\Customize\api\admin\model\RegionModel;
use App\Http\Controllers\api\admin\Base;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use function api\admin\my_config;
use function api\web\get_form_error;

class RegionAction extends Action
{
    public static function country(Base $context , array $param = [])
    {
        $res = RegionModel::country();
        $res = RegionHandler::handleAll($res);
        return self::success('' , $res);
    }

    public static function search(Base $context , array $param = [])
    {
        $type_range = array_keys(my_config('business.type_for_region'));
        $validator = Validator::make($param , [
            'type' => ['sometimes' , Rule::in($type_range)] ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }
        $limit = empty($param['limit']) ? my_config('app.limit') : $param['limit'];
        $res = RegionModel::index($param , $limit);
        $res = RegionHandler::handlePaginator($res);
        return self::success('' , $res);
    }
}
