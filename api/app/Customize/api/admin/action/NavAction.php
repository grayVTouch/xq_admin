<?php


namespace App\Customize\api\admin\action;



use App\Customize\api\admin\handler\NavHandler;
use App\Customize\api\admin\model\NavModel;
use App\Http\Controllers\api\admin\Base;
use Core\Lib\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use function api\admin\get_form_error;
use function api\admin\my_config;
use function core\array_unit;
use function core\obj_to_array;

class NavAction extends Action
{
    public static function index(Base $context , array $param = [])
    {
        $res = NavModel::getAll();
        $res = NavHandler::handleAll($res);
        $res = obj_to_array($res);
        $res = Category::childrens(0 , $res , [
            'id'    => 'id' ,
            'p_id'  => 'p_id' ,
        ] , false , false);
        return self::success('' , $res);
    }

    public static function getByModuleId(Base $context , int $module_id , array $param = []): array
    {
        $res = NavModel::getByModuleId($module_id);
        $res = NavHandler::handleAll($res);
        $res = obj_to_array($res);
        $res = Category::childrens(0 , $res , [
            'id'    => 'id' ,
            'p_id'  => 'p_id' ,
        ] , false , false);
        return self::success('' , $res);
    }

    public static function localUpdate(Base $context , $id , array $param = [])
    {
        $platform_range = array_keys(my_config('business.platform'));
        $bool_range     = array_keys(my_config('business.bool_for_int'));
        $validator = Validator::make($param , [
            'weight'    => 'sometimes|integer',
            'p_id'    => 'sometimes|integer',
            'module_id'    => 'sometimes|integer',
            'enable'   => ['sometimes', Rule::in($bool_range)] ,
            'is_menu'   => ['sometimes', Rule::in($bool_range)] ,
            'platform' => ['sometimes' , Rule::in($platform_range)] ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first());
        }
        $nav = NavModel::find($id);
        if (empty($nav)) {
            return self::error('分类不存在' , '' , 404);
        }
        $param['name']     = $param['name'] === '' ? $nav->name : $param['name'];
        $param['enable']   = $param['enable'] === '' ? $nav->enable : $param['enable'];
        $param['is_menu']   = $param['is_menu'] === '' ? $nav->is_menu : $param['is_menu'];
        $param['weight']    = $param['weight'] === '' ? $nav->weight : $param['weight'];
        $param['p_id']     = $param['p_id'] === '' ? $nav->p_id : $param['p_id'];
        $param['module_id']     = $param['module_id'] === '' ? $nav->module_id : $param['module_id'];
        $param['value']     = $param['value'] === '' ? $nav->value : $param['value'];
        $param['platform']     = $param['platform'] === '' ? $nav->platform : $param['platform'];

        NavModel::updateById($nav->id , array_unit($param , [
            'name' ,
            'enable' ,
            'is_menu' ,
            'weight' ,
            'p_id' ,
            'module_id' ,
            'value' ,
            'platform' ,
        ]));
        return self::success();
    }

    public static function update(Base $context , $id , array $param = [])
    {
        $platform_range = array_keys(my_config('business.platform'));
        $bool_range = array_keys(my_config('business.bool_for_int'));
        $validator = Validator::make($param , [
            'name'    => 'required',
            'enable'   => ['required', Rule::in($bool_range)],
            'is_menu'   => ['required', Rule::in($bool_range)],
            'p_id'    => 'required|integer',
            'weight'    => 'sometimes|integer',
            'module_id'    => 'required|integer',
            'value'    => 'required',
            'platform' => ['required' , Rule::in($platform_range)] ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }
        $nav = NavModel::find($id);
        if (empty($nav)) {
            return self::error('分类不存在' , '' , 404);
        }
        $param['weight'] = $param['weight'] === '' ? 0 : $param['weight'];
        NavModel::updateById($nav->id , array_unit($param , [
            'name' ,
            'enable' ,
            'is_menu' ,
            'weight' ,
            'p_id' ,
            'module_id' ,
            'value' ,
            'platform' ,
        ]));
        return self::success();
    }

    public static function store(Base $context , array $param = [])
    {
        $platform_range = array_keys(my_config('business.platform'));
        $bool_range = array_keys(my_config('business.bool_for_int'));
        $validator = Validator::make($param , [
            'name'    => 'required',
            'enable'  => ['required', Rule::in($bool_range)],
            'is_menu'  => ['required', Rule::in($bool_range)],
            'p_id'    => 'required|integer',
            'weight'  => 'sometimes|integer',
            'module_id'  => 'required|integer',
            'value'    => 'required',
            'platform' => ['required' , Rule::in($platform_range)] ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }
        $param['weight'] = $param['weight'] === '' ? 0 : $param['weight'];
        $id = NavModel::insertGetId(array_unit($param , [
            'name' ,
            'enable' ,
            'is_menu' ,
            'weight' ,
            'p_id' ,
            'module_id' ,
            'value' ,
            'platform' ,
        ]));
        return self::success('' , $id);
    }

    public static function show(Base $context , $id , array $param = [])
    {
        $nav = NavModel::find($id);
        if (empty($nav)) {
            return self::error('分类不存在' , '' , 404);
        }
        $nav = NavHandler::handle($nav);
        return self::success('' , $nav);
    }

    public static function destroy(Base $context , $id , array $param = [])
    {
        $data = NavModel::get();
        $data = obj_to_array($data);
        $data = Category::childrens($id , $data , null , true , false);
        $ids = array_column($data , 'id');
        $count = NavModel::destroy($ids);
        return self::success('' , $count);
    }

    public static function destroyAll(Base $context , array $ids , array $param = [])
    {
        $destroy = [];
        $data = NavModel::get();
        $data = obj_to_array($data);
        foreach ($ids as $v)
        {
            $_data = Category::childrens($v , $data , null , true , false);
            $_ids = array_column($_data , 'id');
            $destroy = array_merge($destroy , $_ids);
        }
        NavModel::destroy($destroy);
        return self::success();
    }

    public static function allExcludeSelfAndChildren(Base $context , int $id , array $param = [])
    {
        $res = NavModel::getAll();
        $res = NavHandler::handleAll($res);
        $res = obj_to_array($res);
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
