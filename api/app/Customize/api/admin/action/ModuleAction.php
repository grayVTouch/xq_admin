<?php


namespace App\Customize\api\admin\action;

use App\Customize\api\admin\handler\ModuleHandler;
use App\Customize\api\admin\model\ModuleModel;
use App\Http\Controllers\api\admin\Base;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use function api\admin\get_form_error;
use function api\admin\my_config;
use function api\admin\my_config_keys;
use function api\admin\parse_order;
use function core\array_unit;
use function core\current_datetime;

class ModuleAction extends Action
{
    public static function index(Base $context , array $param = [])
    {
        $order = $param['order'] === '' ? [] : parse_order($param['order'] , '|');
        $size = $param['size'] === '' ? my_config('app.limit') : $param['size'];
        $res = ModuleModel::index($param , $order , $size);
        $res = ModuleHandler::handlePaginator($res);
        return self::success('' , $res);
    }

    public static function update(Base $context , $id , array $param = [])
    {
        $bool_for_int = my_config_keys('business.bool_for_int');

        $validator = Validator::make($param , [
//            'name'          => 'required' ,
            'is_enabled'    => ['required' , Rule::in($bool_for_int)] ,
            'is_default'    => ['required' , Rule::in($bool_for_int)] ,
            'is_auth'       => ['required' , Rule::in($bool_for_int)] ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , $validator->errors());
        }
        $res = ModuleModel::find($id);
        if (empty($res)) {
            return self::error('模块不存在' , '' , 404);
        }
        $param['weight'] = $param['weight'] === '' ? 0 : $param['weight'];
        try {
            DB::beginTransaction();
            ModuleModel::updateById($res->id , array_unit($param , [
//                'name' ,
                'description' ,
                'is_enabled' ,
                'is_auth' ,
                'is_default' ,
                'weight' ,
            ]));
            if ($param['is_default']) {
                ModuleModel::setNotDefaultByExcludeId($res->id);
            }
            DB::commit();
            return self::success('操作成功');
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function localUpdate(Base $context , $id , array $param = [])
    {
        $bool_for_int = my_config_keys('business.bool_for_int');

        $validator = Validator::make($param , [
            'is_enabled'        => ['sometimes' , Rule::in($bool_for_int)] ,
            'is_auth'          => ['sometimes' , Rule::in($bool_for_int)] ,
        ]);

        if ($validator->fails()) {
            return self::error($validator->errors()->first() , $validator->errors());
        }

        $res = ModuleModel::find($id);

        if (empty($res)) {
            return self::error('模块不存在' , '' , 404);
        }

        $param['name']   = $param['name'] === '' ? $res->name : $param['name'];
        $param['weight'] = $param['weight'] === '' ? $res->weight : $param['weight'];
        $param['is_enabled'] = $param['is_enabled'] === '' ? $res->enable : $param['is_enabled'];
        $param['is_default'] = $param['is_default'] === '' ? $res->default : $param['is_default'];

        try {
            DB::beginTransaction();

            ModuleModel::updateById($res->id , array_unit($param , [
                'name' ,
                'description' ,
                'is_enabled' ,
                'is_auth' ,
                'weight' ,
                'is_default' ,
            ]));

            if ($param['is_default']) {
                ModuleModel::setNotDefaultByExcludeId($res->id);
            }

            DB::commit();

            return self::success('操作成功');
        } catch(Exception $e) {

            DB::rollBack();

            throw $e;
        }
    }

    public static function store(Base $context , array $param = [])
    {
        $bool_for_int = my_config_keys('business.bool_for_int');
        $validator = Validator::make($param , [
            'name'          => 'required' ,
            'is_enabled'        => ['required' , Rule::in($bool_for_int)] ,
            'is_default'       => ['required' , Rule::in($bool_for_int)] ,
            'is_auth'          => ['required' , Rule::in($bool_for_int)] ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , $validator->errors());
        }
        $param['weight']        = $param['weight'] === '' ? 0 : $param['weight'];
        $param['created_at']   = current_datetime();
        if (!empty(ModuleModel::findByName($param['name']))) {
            return self::error('名称已经被使用');
        }
        try {
            DB::beginTransaction();
            $id = ModuleModel::insertGetId(array_unit($param , [
                'name' ,
                'description' ,
                'is_enabled' ,
                'is_auth' ,
                'weight' ,
                'is_default' ,
            ]));
            if ($param['is_default']) {
                ModuleModel::setNotDefaultByExcludeId($id);
            }
            DB::commit();
            return self::success('' , $id);
        } catch(Exception $e) {

            DB::rollBack();

            throw $e;
        }
    }

    public static function show(Base $context , $id , array $param = [])
    {
        $res = ModuleModel::find($id);
        if (empty($res)) {
            return self::error('模块不存在' , '' , 404);
        }
        $res = ModuleHandler::handle($res);
        return self::success('' , $res);
    }

    public static function destroy(Base $context , $id , array $param = [])
    {
        $count = ModuleModel::destroy($id);
        return self::success('操作成功' , $count);
    }

    public static function destroyAll(Base $context , array $ids , array $param = [])
    {
        $count = ModuleModel::destroy($ids);
        return self::success('操作成功' , $count);
    }

    public static function all(Base $context , array $param = [])
    {
        $res = ModuleModel::get();
        $res = ModuleHandler::handleAll($res);
        return self::success('' , $res);
    }
}
