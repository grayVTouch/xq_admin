<?php


namespace App\Customize\api\admin_v1\action;



use App\Customize\api\admin_v1\handler\AdminHandler;
use App\Customize\api\admin_v1\model\AdminModel;
use App\Http\Controllers\api\admin_v1\Base;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use function api\admin_v1\get_form_error;
use function api\admin_v1\my_config;
use function api\admin_v1\parse_order;
use function api\admin_v1\user;
use function core\array_unit;

class AdminAction extends Action
{
    public static function info(Base $context , array $param)
    {
        $user = user();
        return self::success('' , [
            'user' => $user
        ]);
    }

    public static function search(Base $context , $value , array $param = [])
    {
        if (empty($value)) {
            return self::error('请提供搜索值');
        }
        $res = AdminModel::search($value);
        $res = AdminHandler::handleAll($res);
        return self::success('' , $res);
    }

    public static function index(Base $context , array $param = [])
    {
        $order = $param['order'] === '' ? [] : parse_order($param['order'] , '|');
        $limit = $param['limit'] === '' ? my_config('app.limit') : $param['limit'];
        $paginator = AdminModel::index($param , $order , $limit);
        $paginator = AdminHandler::handlePaginator($paginator);
        return self::success('' , $paginator);
    }

    public static function update(Base $context , $id , array $param = [])
    {
        $sex_range = array_keys(my_config('business.sex_for_user'));
        $validator = Validator::make($param , [
            'username' => 'required|min:6' ,
            'sex' => ['required' , Rule::in($sex_range)] ,
            'role_id' => 'required|integer' ,
        ]);
        if ($validator->fails()) {
            return self::error('表单错误，请检查' , get_form_error($validator));
        }
        // 检查用户名是否已经存在
        $res = AdminModel::find($id);
        if (empty($res)) {
            return self::error('用户不存在' , '' , 404);
        }
        if ($res->username !== $param['username'] && AdminModel::findByUsername($param['username'])) {
            return self::error('用户名已经被使用');
        }
        if ($res->is_root && $res->id != user()->id) {
            // 超级管理员
            return self::error('超级管理员仅不允许他人操作！' , '' , 403);
        }
        $param['birthday']      = in_array($param['birthday'] , ['' , 'null']) ? null : $param['birthday'];
        $param['password']      = $param['password'] === '' ? $res->password : Hash::make($param['password']);
        AdminModel::updateById($res->id , array_unit($param , [
            'username' ,
            'password' ,
            'birthday' ,
            'avatar' ,
            'phone' ,
            'email' ,
            'role_id' ,
        ]));
        return self::success();
    }

    public static function store(Base $context , array $param = [])
    {
        $sex_range = array_keys(my_config('business.sex_for_user'));
        $validator = Validator::make($param , [
            'username' => 'required|min:6' ,
            'password' => 'required' ,
            'sex' => ['required' , Rule::in($sex_range)] ,
            'role_id' => 'required|integer' ,
        ]);
        if ($validator->fails()) {
            return self::error('表单错误，请检查' , get_form_error($validator));
        }
        $res = AdminModel::findByUsername($param['username']);
        if (!empty($res)) {
            return self::error('用户名已经被使用');
        }
        $param['birthday']      = in_array($param['birthday'] , ['' , 'null']) ? null : $param['birthday'];
        $param['password']      = Hash::make($param['password']);
        $id = AdminModel::insertGetId(array_unit($param , [
            'username' ,
            'password' ,
            'birthday' ,
            'avatar' ,
            'phone' ,
            'email' ,
            'role_id' ,
        ]));
        return self::success('' , $id);
    }

    public static function show(Base $context , $id , array $param = [])
    {
        $res = AdminModel::find($id);
        if (empty($res)) {
            return self::error('用户不存在' , '' , 404);
        }
        $res = AdminHandler::handle($res);
        return self::success('' , $res);
    }

    public static function destroy(Base $context , $id , array $param = [])
    {
        $res = AdminModel::find($id);
        if (empty($res)) {
            return self::error('用户不存在' , '' , 404);
        }
        if ($res->is_root) {
            return self::error('禁止对超级管理员操作' , '' , 403);
        }
        $count = AdminModel::delById($id);
        return self::success('' , $count);
    }

    public static function destroyAll(Base $context , array $ids , array $param = [])
    {
        $res = AdminModel::getByIds($ids);
        foreach ($res as $v)
        {
            if ($v->is_root) {
                return self::error('包含超级管理员，禁止操作' , '' , 403);
            }
        }
        $count = AdminModel::delByIds($ids);
        return self::success('' , $count);
    }
}
