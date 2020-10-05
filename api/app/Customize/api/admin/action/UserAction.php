<?php


namespace App\Customize\api\admin\action;



use App\Customize\api\admin\handler\UserHandler;
use App\Customize\api\admin\model\AdminModel;
use App\Customize\api\admin\model\UserModel;
use App\Customize\api\admin\util\ResourceUtil;
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

class UserAction extends Action
{

    public static function search(Base $context , array $param = [])
    {
        $limit = empty($param['limit']) ? my_config('app.limit') : $param['limit'];
        $res = UserModel::search($param['value'] , $limit);
        $res = UserHandler::handlePaginator($res);
        return self::success('' , $res);
    }

    public static function index(Base $context , array $param = [])
    {
        $order = $param['order'] === '' ? [] : parse_order($param['order'] , '|');
        $limit = $param['limit'] === '' ? my_config('app.limit') : $param['limit'];
        $paginator = UserModel::index($param , $order , $limit);
        $paginator = UserHandler::handlePaginator($paginator);
        return self::success('' , $paginator);
    }

    public static function update(Base $context , $id , array $param = [])
    {
        $sex_range = my_config_keys('business.sex_for_user');

        $validator = Validator::make($param , [
            'username'  => 'required' ,
            'sex'       => ['required' , Rule::in($sex_range)] ,
        ]);

        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }

        // 检查用户名是否已经存在
        $res = UserModel::find($id);

        if (empty($res)) {
            return self::error('用户不存在' , '' , 404);
        }

        if ($res->username !== $param['username'] && UserModel::findByUsername($param['username'])) {
            return self::error('用户名已经被使用');
        }

        $param['user_group_id'] = $param['user_group_id'] === '' ? 0 : $param['user_group_id'];
        $param['birthday']      = in_array($param['birthday'] , ['' , 'null']) ? null : $param['birthday'];
        $param['password']      = $param['password'] === '' ? $res->password : Hash::make($param['password']);

        try {
            DB::beginTransaction();
            UserModel::updateById($res->id , array_unit($param , [
                'username' ,
                'password' ,
                'birthday' ,
                'avatar' ,
                'phone' ,
                'email' ,
                'sex' ,
                'user_group_id' ,
            ]));
            ResourceUtil::used($param['avatar']);
            if ($res->avatar !== $param['avatar']) {
                ResourceUtil::delete($res->avatar);
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
        $sex_range = my_config_keys('business.sex_for_user');

        $validator = Validator::make($param , [
            'username' => 'required' ,
            'password' => 'required' ,
            'sex' => ['required' , Rule::in($sex_range)] ,
        ]);

        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }

        $res = UserModel::findByUsername($param['username']);

        if (!empty($res)) {
            return self::error('用户名已经被使用');
        }

        $param['user_group_id'] = $param['user_group_id'] === '' ? 0 : $param['user_group_id'];
        $param['birthday']      = in_array($param['birthday'] , ['' , 'null']) ? null : $param['birthday'];
        $param['password']      = Hash::make($param['password']);

        try {
            DB::beginTransaction();
            $id = UserModel::insertGetId(array_unit($param , [
                'username' ,
                'password' ,
                'birthday' ,
                'avatar' ,
                'phone' ,
                'email' ,
                'sex' ,
                'user_group_id' ,
            ]));
            ResourceUtil::used($param['avatar']);
            DB::commit();
            return self::success('' , $id);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function show(Base $context , $id , array $param = [])
    {
        $res = UserModel::find($id);

        if (empty($res)) {
            return self::error('用户不存在' , '' , 404);
        }

        $res = UserHandler::handle($res);

        return self::success('' , $res);
    }

    public static function destroy(Base $context , $id , array $param = [])
    {
        $res = UserModel::find($id);
        if (empty($res)) {
            return self::error('记录不存在' , '' , 404);
        }
        try {
            DB::beginTransaction();
            $count = UserModel::destroy($res->id);
            ResourceUtil::delete($res->avatar);
            DB::commit();
            return self::success('' , $count);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function destroyAll(Base $context , array $ids , array $param = [])
    {

        $res = UserModel::find($ids);
        try {
            DB::beginTransaction();
            foreach ($res as $v)
            {
                ResourceUtil::delete($v->avatar);
            }
            $count = UserModel::destroy($ids);
            DB::commit();
            return self::success('' , $count);
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
