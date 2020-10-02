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
use function api\admin\parse_order;
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
            'name'          => 'required' ,
            'enable'        => ['required' , Rule::in($bool_for_int)] ,
            'default'       => ['required' , Rule::in($bool_for_int)] ,
            'auth'          => ['required' , Rule::in($bool_for_int)] ,
            'auth_password' => 'sometimes|min:4' ,
        ]);

        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }

        $res = ModuleModel::find($id);

        if (empty($res)) {
            return self::error('模块不存在' , '' , 404);
        }

        $param['weight']        = $param['weight'] === '' ? 0 : $param['weight'];
        $param['auth_password'] = $param['auth'] ?
            ($param['auth_password'] === '' ?
                $res->auth_password :
                Hash::make($param['auth_password'])
            ) :
            '';

        try {
            DB::beginTransaction();

            ModuleModel::updateById($res->id , array_unit($param , [
                'name' ,
                'description' ,
                'enable' ,
                'auth' ,
                'auth_password' ,
                'weight' ,
                'default' ,
            ]));

            if ($param['default']) {
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
        $bool_for_int = array_keys(my_config('business.bool_for_int'));

        $validator = Validator::make($param , [
            'enable'        => ['sometimes' , Rule::in($bool_for_int)] ,
            'auth'          => ['sometimes' , Rule::in($bool_for_int)] ,
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
        $param['enable'] = $param['enable'] === '' ? $res->enable : $param['enable'];
        $param['default'] = $param['default'] === '' ? $res->default : $param['default'];
        $param['auth_password'] = $param['auth'] !== '' ?
            ($param['auth'] ?
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
                'enable' ,
                'auth' ,
                'auth_password' ,
                'weight' ,
                'default' ,
            ]));

            if ($param['default']) {
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
        $bool_for_int = array_keys(my_config('business.bool_for_int'));

        $validator = Validator::make($param , [
            'name'          => 'required' ,
            'enable'        => ['required' , Rule::in($bool_for_int)] ,
            'default'       => ['required' , Rule::in($bool_for_int)] ,
            'auth'          => ['required' , Rule::in($bool_for_int)] ,
            'auth_password' => 'sometimes|min:4' ,
        ]);

        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }

        if ($param['auth']) {
            if (empty($param['auth_password'])) {
                return self::error('表单错误' , [
                    'auth_password' => '请提供认证密码' ,
                ]);
            }
        }

        $param['weight']        = $param['weight'] === '' ? 0 : $param['weight'];
        $param['auth_password'] = $param['auth'] ? Hash::make($param['auth_password']) : '';
        $param['created_at']   = current_time();

        try {
            DB::beginTransaction();

            $id = ModuleModel::insertGetId(array_unit($param , [
                'name' ,
                'description' ,
                'enable' ,
                'auth' ,
                'auth_password' ,
                'weight' ,
                'default' ,
            ]));

            if ($param['default']) {
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
