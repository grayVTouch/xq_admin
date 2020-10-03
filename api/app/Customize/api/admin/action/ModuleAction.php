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
        $limit = $param['limit'] === '' ? my_config('app.limit') : $param['limit'];
        $paginator = ModuleModel::index($param , $order , $limit);
        $paginator = ModuleHandler::handlePaginator($paginator);
        return self::success('' , $paginator);
    }

    public static function update(Base $context , $id , array $param = [])
    {
        $bool_for_int = my_config_keys('business.bool_for_int');

        $validator = Validator::make($param , [
            'name'          => 'required' ,
            'is_enabled'    => ['required' , Rule::in($bool_for_int)] ,
            'is_default'    => ['required' , Rule::in($bool_for_int)] ,
            'is_auth'       => ['required' , Rule::in($bool_for_int)] ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }
        $res = ModuleModel::find($id);
        if (empty($res)) {
            return self::error('模块不存在' , '' , 404);
        }
        $param['weight'] = $param['weight'] === '' ? 0 : $param['weight'];
        try {
            DB::beginTransaction();
            ModuleModel::updateById($res->id , array_unit($param , [
                'name' ,
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
            return self::success();
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
            'auth_password' => 'sometimes|min:4' ,
        ]);

        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }

        $res = ModuleModel::find($id);

        if (empty($res)) {
            return self::error('模块不存在' , '' , 404);
        }

        $param['name']   = $param['name'] === '' ? $res->name : $param['name'];
        $param['weight'] = $param['weight'] === '' ? $res->weight : $param['weight'];
        $param['is_enabled'] = $param['is_enabled'] === '' ? $res->enable : $param['is_enabled'];
        $param['is_default'] = $param['is_default'] === '' ? $res->default : $param['is_default'];
        $param['auth_password'] = $param['is_auth'] !== '' ?
            ($param['is_auth'] ?
                ($param['auth_password'] === '' ?
                    $res->auth_password :
                    Hash::make($param['auth_password'])
                ) :
                ''
            ) :
            $res->auth_password;

        try {
            DB::beginTransaction();

            ModuleModel::updateById($res->id , array_unit($param , [
                'name' ,
                'description' ,
                'is_enabled' ,
                'is_auth' ,
                'auth_password' ,
                'weight' ,
                'is_default' ,
            ]));

            if ($param['is_default']) {
                ModuleModel::setNotDefaultByExcludeId($res->id);
            }

            DB::commit();

            return self::success();
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
            'auth_password' => 'sometimes|min:4' ,
        ]);

        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }

        if ($param['is_auth']) {
            if (empty($param['auth_password'])) {
                return self::error('表单错误' , [
                    'auth_password' => '请提供认证密码' ,
                ]);
            }
        }

        $param['weight']        = $param['weight'] === '' ? 0 : $param['weight'];
        $param['auth_password'] = $param['is_auth'] ? Hash::make($param['auth_password']) : '';
        $param['created_at']   = current_datetime();

        try {
            DB::beginTransaction();

            $id = ModuleModel::insertGetId(array_unit($param , [
                'name' ,
                'description' ,
                'is_enabled' ,
                'is_auth' ,
                'auth_password' ,
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
        $res = ModuleModel::handle($res);
        return self::success('' , $res);
    }

    public static function destroy(Base $context , $id , array $param = [])
    {
        $count = ModuleModel::destroy($id);
        return self::success('' , $count);
    }

    public static function destroyAll(Base $context , array $ids , array $param = [])
    {
        $count = ModuleModel::destroy($ids);
        return self::success('' , $count);
    }

    public static function all(Base $context , array $param = [])
    {
        $res = ModuleModel::get();
        $res = ModuleHandler::handleAll($res);
        return self::success('' , $res);
    }
}
