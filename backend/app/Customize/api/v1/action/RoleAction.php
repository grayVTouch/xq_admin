<?php


namespace App\Customize\api\v1\action;



use App\Customize\api\v1\handler\RoleHandler;
use App\Customize\api\v1\model\RoleModel;
use App\Http\Controllers\api\v1\Base;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use function api\v1\get_form_error;
use function api\v1\my_config;
use function api\v1\parse_order;
use function core\array_unit;
use function core\convert_obj;
use function core\obj_to_array;

class RoleAction extends Action
{
    public static function index(Base $context , array $param = [])
    {
        $order = $param['order'] === '' ? [] : parse_order($param['order'] , '|');
        $limit = $param['limit'] === '' ? my_config('app.limit') : $param['limit'];
        $paginator = RoleModel::index($param , $order , $limit);
        $paginator = RoleHandler::handlePaginator($paginator);
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
        $role = RoleModel::find($id);
        if (empty($role)) {
            return self::error('角色不存在' , 404);
        }
        $param['weight'] = $param['weight'] === '' ? 0 : $param['weight'];
        RoleModel::updateById($role->id , array_unit($param , [
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
        $id = RoleModel::insertGetId(array_unit($param , [
            'name' ,
            'weight' ,
        ]));
        return self::success($id);
    }

    public static function show(Base $context , $id , array $param = [])
    {
        $role = RoleModel::find($id);
        if (empty($role)) {
            return self::error('角色不存在' , 404);
        }
        $role = RoleModel::handle($role);
        return self::success($role);
    }

    public static function destroy(Base $context , $id , array $param = [])
    {
        $count = RoleModel::delById($id);
        return self::success($count);
    }

    public static function destroyAll(Base $context , array $id_list , array $param = [])
    {
        $count = RoleModel::delByIds($id_list);
        return self::success($count);
    }



}
