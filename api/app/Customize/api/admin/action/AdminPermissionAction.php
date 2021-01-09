<?php


namespace App\Customize\api\admin\action;



use App\Customize\api\admin\handler\AdminPermissionHandler;
use App\Customize\api\admin\model\AdminPermissionModel;
use App\Http\Controllers\api\admin\Base;
use Core\Lib\Category;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use function api\admin\get_form_error;
use function api\admin\my_config_keys;
use function api\admin\parse_order;
use function api\admin\user;
use function api\admin\my_config;
use function core\array_unit;
use function core\object_to_array;

class AdminPermissionAction extends Action
{
    public static function index(Base $context , array $param = [])
    {
        $res = AdminPermissionModel::getAll();
        $res = AdminPermissionHandler::handleAll($res);
        $res = object_to_array($res);
        $res = Category::childrens(0 , $res , [
            'id'    => 'id' ,
            'p_id'  => 'p_id' ,
        ] , false , false);
        return self::success('' , $res);
    }

    public static function localUpdate(Base $context , $id , array $param = [])
    {
        $type_range = my_config('business.type_for_admin_permission');
        $bool_range = my_config_keys('business.bool_for_int');
        $validator = Validator::make($param , [
            'type'      => ['sometimes'   , Rule::in($type_range)],
            'is_menu'   => ['sometimes', Rule::in($bool_range)],
            'is_view'   => ['sometimes', Rule::in($bool_range)],
            'weight'    => 'sometimes|integer',
            'p_id'    => 'sometimes|integer',
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }
        $permission = AdminPermissionModel::find($id);
        if (empty($permission)) {
            return self::error('权限不存在' , '' , 404);
        }
        $param['value']     = $param['value'] === '' ? $permission->value : $param['value'];
        $param['cn']        = $param['cn'] === '' ?  $permission->cn : $param['cn'];
        $param['en']        = $param['en'] === '' ? $permission->en : $param['en'];
        $param['description'] = $param['description'] === '' ? $permission->description : $param['description'];
        $param['type']      = $param['type'] === '' ? $permission->type : $param['type'];
        $param['is_menu']   = $param['is_menu'] === '' ? $permission->is_menu : $param['is_menu'];
        $param['is_view']   = $param['is_view'] === '' ? $permission->is_view : $param['is_view'];
        $param['enable']   = $param['enable'] === '' ? $permission->enable : $param['enable'];
        $param['weight']    = $param['weight'] === '' ? $permission->weight : $param['weight'];
        $param['s_ico']     = $param['s_ico'] === '' ? $permission->s_ico : $param['s_ico'];
        $param['b_ico']     = $param['b_ico'] === '' ? $permission->b_ico : $param['b_ico'];
        $param['p_id']     = $param['p_id'] === '' ? $permission->p_id : $param['p_id'];

        AdminPermissionModel::updateById($permission->id , array_unit($param , [
            'value' ,
            'cn' ,
            'en' ,
            'description' ,
            'type' ,
            'is_menu' ,
            'is_view' ,
            'enable' ,
            'weight' ,
            's_ico' ,
            'b_ico' ,
            'p_id' ,
        ]));
        return self::success('操作成功');
    }

    public static function update(Base $context , $id , array $param = [])
    {
        $type_range = my_config('business.type_for_admin_permission');
        $bool_range = my_config_keys('business.bool_for_int');
        $validator = Validator::make($param , [
            'value'     => 'required' ,
            'type'      => ['required'   , Rule::in($type_range)],
            'is_menu'   => ['required', Rule::in($bool_range)],
            'is_view'   => ['required', Rule::in($bool_range)],
            'enable'   => ['required', Rule::in($bool_range)],
            'p_id'    => 'required|integer',
            'weight'    => 'sometimes|integer',
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }
        $permission = AdminPermissionModel::find($id);
        if (empty($permission)) {
            return self::error('权限不存在' , '' , 404);
        }
        $param['weight']     = $param['weight'] === '' ? 0 : $param['weight'];
        AdminPermissionModel::updateById($permission->id , array_unit($param , [
            'value' ,
            'cn' ,
            'en' ,
            'description' ,
            'type' ,
            'is_menu' ,
            'is_view' ,
            'enable' ,
            'weight' ,
            's_ico' ,
            'b_ico' ,
            'p_id' ,
        ]));
        return self::success('操作成功');
    }

    public static function store(Base $context , array $param = [])
    {
        $type_range = my_config('business.type_for_admin_permission');
        $bool_range = my_config_keys('business.bool_for_int');
        $validator = Validator::make($param , [
            'value'     => 'required' ,
            'type'      => ['required'   , Rule::in($type_range)],
            'is_menu'   => ['required', Rule::in($bool_range)],
            'is_view'   => ['required', Rule::in($bool_range)],
            'enable'   => ['required', Rule::in($bool_range)],
            'weight'    => 'sometimes|integer',
            'p_id'    => 'required|integer',
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }
        $param['weight'] = $param['weight'] === '' ? 0 : $param['weight'];
        $id = AdminPermissionModel::insertGetId(array_unit($param , [
            'value' ,
            'cn' ,
            'en' ,
            'description' ,
            'type' ,
            'is_menu' ,
            'is_view' ,
            'enable' ,
            'weight' ,
            's_ico' ,
            'b_ico' ,
            'p_id' ,
        ]));
        return self::success('' , $id);
    }

    public static function show(Base $context , $id , array $param = [])
    {
        $permission = AdminPermissionModel::find($id);
        if (empty($permission)) {
            return self::error('权限不存在' , '' , 404);
        }
        $permission = AdminPermissionHandler::handle($permission);
        return self::success('' , $permission);
    }

    public static function destroy(Base $context , $id , array $param = [])
    {
        $data = AdminPermissionModel::get();
        $data = object_to_array($data);
        $data = Category::childrens($id , $data , null , true , false);
        $ids = array_column($data , 'id');
        $count = AdminPermissionModel::destroy($ids);
        return self::success('操作成功' , $count);
    }

    public static function destroyAll(Base $context , array $ids , array $param = [])
    {
        $destroy = [];
        $data = AdminPermissionModel::get();
        $data = object_to_array($data);
        foreach ($ids as $v)
        {
            $_data = Category::childrens($v , $data , null , true , false);
            $_ids = array_column($_data , 'id');
            $destroy = array_merge($destroy , $_ids);
        }
        AdminPermissionModel::destroy($destroy);
        return self::success('操作成功');
    }

    public static function allExcludeSelfAndChildren(Base $context , int $id , array $param = [])
    {
        $res = AdminPermissionModel::getAll();
        $res = AdminPermissionHandler::handleAll($res);
        $res = object_to_array($res);
        $exclude = Category::childrens($id , $res , null , true , false);
        $exclude_ids = array_column($exclude , 'id');
        for ($i = 0; $i < count($res); ++$i)
        {
            $cur = $res[$i];
            if (in_array($cur['id'] , $exclude_ids)) {
                unset($res[$i]);
                $i--;
            }
        }
        $res = Category::childrens(0 , $res , [
            'id'    => 'id' ,
            'p_id'  => 'p_id' ,
        ] , false , false);
        return self::success('' , $res);
    }

}
