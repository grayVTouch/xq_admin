<?php


namespace App\Customize\api\admin_v1\action;

use App\Customize\api\admin_v1\handler\ModuleHandler;
use App\Customize\api\admin_v1\model\ModuleModel;
use App\Http\Controllers\api\admin_v1\Base;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use function api\admin_v1\get_form_error;
use function api\admin_v1\my_config;
use function api\admin_v1\parse_order;
use function core\array_unit;
use function core\current_time;

class ModuleAction extends Action
{
    public static function index(Base $context , array $param = [])
    {
        $order = $param['order'] === '' ? [] : parse_order($param['order'] , '|');
        $limit = $param['limit'] === '' ? my_config('app.limit') : $param['limit'];
        $paginator = ModuleModel::index($param , $order , $limit);
        $paginator = ModuleHandler::handlePaginator($paginator);
        return self::success('' , $paginator);
    }

    public static function update(Base $context , $id , array $param = [])
    {
        $bool_for_int = array_keys(my_config('business.bool_for_int'));
        $validator = Validator::make($param , [
            'name' => 'required' ,
            'enable' => ['required' , Rule::in($bool_for_int)] ,
        ]);
        if ($validator->fails()) {
            return self::error('表单错误，请检查' , get_form_error($validator));
        }
        $res = ModuleModel::find($id);
        if (empty($res)) {
            return self::error('模块不存在' , '' , 404);
        }
        $param['weight'] = $param['weight'] === '' ? 0 : $param['weight'];
        ModuleModel::updateById($res->id , array_unit($param , [
            'name' ,
            'weight' ,
            'enable' ,
        ]));
        return self::success();
    }

    public static function localUpdate(Base $context , $id , array $param = [])
    {
        $bool_for_int = array_keys(my_config('business.bool_for_int'));
        $validator = Validator::make($param , [
            'enable' => ['sometimes' , Rule::in($bool_for_int)] ,
        ]);
        if ($validator->fails()) {
            return self::error('表单错误，请检查' , get_form_error($validator));
        }
        $res = ModuleModel::find($id);
        if (empty($res)) {
            return self::error('模块不存在' , '' , 404);
        }
        $param['name'] = $param['name'] === '' ? $res->name : $param['name'];
        $param['weight'] = $param['weight'] === '' ? $res->weight : $param['weight'];
        $param['enable'] = $param['enable'] === '' ? $res->enable : $param['enable'];
        ModuleModel::updateById($res->id , array_unit($param , [
            'name' ,
            'weight' ,
            'enable' ,
        ]));
        return self::success();
    }

    public static function store(Base $context , array $param = [])
    {
        $bool_for_int = array_keys(my_config('business.bool_for_int'));
        $validator = Validator::make($param , [
            'name' => 'required' ,
            'enable' => ['required' , Rule::in($bool_for_int)] ,
        ]);
        if ($validator->fails()) {
            return self::error('表单错误，请检查' , get_form_error($validator));
        }
        $param['weight'] = $param['weight'] === '' ? 0 : $param['weight'];
        $param['create_time'] = current_time();
        $id = ModuleModel::insertGetId(array_unit($param , [
            'name' ,
            'weight' ,
            'enable' ,
            'create_time'
        ]));
        return self::success('' , $id);
    }

    public static function show(Base $context , $id , array $param = [])
    {
        $res = ModuleModel::find($id);
        if (empty($res)) {
            return self::error('模块不存在' , '' , 404);
        }
        $res = ModuleModel::handle($res);
        return self::success('' , $res);
    }

    public static function destroy(Base $context , $id , array $param = [])
    {
        $count = ModuleModel::delById($id);
        return self::success('' , $count);
    }

    public static function destroyAll(Base $context , array $ids , array $param = [])
    {
        $count = ModuleModel::delByIds($ids);
        return self::success('' , $count);
    }

    public static function all(Base $context , array $param = [])
    {
        $res = ModuleModel::get();
        $res = ModuleHandler::handleAll($res);
        return self::success('' , $res);
    }
}
