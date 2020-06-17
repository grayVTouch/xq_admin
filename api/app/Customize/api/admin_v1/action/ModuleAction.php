<?php


namespace App\Customize\api\admin_v1\action;

use App\Customize\api\admin_v1\handler\ModuleHandler;
use App\Customize\api\admin_v1\model\ModuleModel;
use App\Http\Controllers\api\admin_v1\Base;
use Illuminate\Support\Facades\Validator;
use function api\admin_v1\get_form_error;
use function api\admin_v1\my_config;
use function api\admin_v1\parse_order;
use function core\array_unit;

class ModuleAction extends Action
{
    public static function index(Base $context , array $param = [])
    {
        $order = $param['order'] === '' ? [] : parse_order($param['order'] , '|');
        $limit = $param['limit'] === '' ? my_config('app.limit') : $param['limit'];
        $paginator = ModuleModel::index($param , $order , $limit);
        $paginator = ModuleHandler::handlePaginator($paginator);
        return self::success($paginator);
    }

    public static function update(Base $context , $id , array $param = [])
    {
        $validator = Validator::make($param , [
            'name' => 'required' ,
        ]);
        if ($validator->fails()) {
            return self::error(get_form_error($validator));
        }
        $role = ModuleModel::find($id);
        if (empty($role)) {
            return self::error('角色不存在' , 404);
        }
        $param['weight'] = $param['weight'] === '' ? 0 : $param['weight'];
        ModuleModel::updateById($role->id , array_unit($param , [
            'name' ,
            'weight' ,
        ]));
        return self::success();
    }

    public static function store(Base $context , array $param = [])
    {
        $validator = Validator::make($param , [
            'name' => 'required' ,
        ]);
        if ($validator->fails()) {
            return self::error(get_form_error($validator));
        }
        $param['weight'] = $param['weight'] === '' ? 0 : $param['weight'];
        $id = ModuleModel::insertGetId(array_unit($param , [
            'name' ,
            'weight' ,
        ]));
        return self::success($id);
    }

    public static function show(Base $context , $id , array $param = [])
    {
        $role = ModuleModel::find($id);
        if (empty($role)) {
            return self::error('角色不存在' , 404);
        }
        $role = ModuleModel::handle($role);
        return self::success($role);
    }

    public static function destroy(Base $context , $id , array $param = [])
    {
        $count = ModuleModel::delById($id);
        return self::success($count);
    }

    public static function destroyAll(Base $context , array $ids , array $param = [])
    {
        $count = ModuleModel::delByIds($ids);
        return self::success($count);
    }

    public static function all(Base $context , array $param = [])
    {
        $res = ModuleModel::get();
        $res = ModuleHandler::handleAll($res);
        return self::success($res);
    }
}
