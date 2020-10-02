<?php


namespace App\Customize\api\admin\action;

use App\Customize\api\admin\handler\PositionHandler;
use App\Customize\api\admin\model\ModuleModel;
use App\Customize\api\admin\model\PositionModel;
use App\Http\Controllers\api\admin\Base;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use function api\admin\get_form_error;
use function api\admin\my_config;
use function api\admin\parse_order;
use function core\array_unit;

class PositionAction extends Action
{
    public static function index(Base $context , array $param = [])
    {
        $order = $param['order'] === '' ? [] : parse_order($param['order'] , '|');
        $limit = $param['limit'] === '' ? my_config('app.limit') : $param['limit'];
        $paginator = PositionModel::index($param , $order , $limit);
        $paginator = PositionHandler::handlePaginator($paginator);
        return self::success('' , $paginator);
    }

    public static function update(Base $context , $id , array $param = [])
    {
        $platform_range = array_keys(my_config('business.platform'));
        $validator = Validator::make($param , [
            'name' => 'required' ,
            'value' => 'required' ,
            'platform' => ['required' , Rule::in($platform_range)] ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }
        $res = PositionModel::find($id);
        if (empty($res)) {
            return self::error('位置不存在' , '' , 404);
        }
        PositionModel::updateById($res->id , array_unit($param , [
            'value' ,
            'name' ,
            'description' ,
            'platform' ,
        ]));
        return self::success();
    }

    public static function store(Base $context , array $param = [])
    {
        $platform_range = array_keys(my_config('business.platform'));
        $validator = Validator::make($param , [
            'name' => 'required' ,
            'value' => 'required' ,
            'platform' => ['required' , Rule::in($platform_range)] ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }
        $res = PositionModel::getByPlatformAndValue($param['platform'] , $param['value']);
        if (!empty($tag)) {
            return self::error('位置已经存在');
        }
        $id = PositionModel::insertGetId(array_unit($param , [
            'value' ,
            'name' ,
            'description' ,
            'platform' ,
        ]));
        return self::success('' , $id);
    }

    public static function show(Base $context , $id , array $param = [])
    {
        $role = PositionModel::find($id);
        if (empty($role)) {
            return self::error('位置不存在' , '' , 404);
        }
        $role = PositionModel::handle($role);
        return self::success('' , $role);
    }

    public static function destroy(Base $context , $id , array $param = [])
    {
        $count = PositionModel::destroy($id);
        return self::success('' , $count);
    }

    public static function destroyAll(Base $context , array $ids , array $param = [])
    {
        $count = PositionModel::destroy($ids);
        return self::success('' , $count);
    }

    public static function search(Base $context , $value , array $param = [])
    {
        if (empty($value)) {
            return self::error('请提供搜索值');
        }
        $res = PositionModel::search($value);
        $res = PositionHandler::handleAll($res);
        return self::success('' , $res);
    }

    public static function all(Base $context , array $param = [])
    {
        $res = PositionModel::all();
        $res = PositionHandler::handleAll($res);
        return self::success('' , $res);
    }
}
