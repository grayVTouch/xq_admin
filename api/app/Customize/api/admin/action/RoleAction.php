<?php


namespace App\Customize\api\admin\action;



use App\Customize\api\admin\handler\AdminPermissionHandler;
use App\Customize\api\admin\handler\RoleHandler;
use App\Customize\api\admin\model\AdminPermissionModel;
use App\Customize\api\admin\model\RoleModel;
use App\Customize\api\admin\model\RolePermissionPivot;
use App\Http\Controllers\api\admin\Base;
use Core\Lib\Category;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use function api\admin\get_form_error;
use function api\admin\my_config;
use function api\admin\parse_order;
use function core\array_unit;
use function core\convert_object;
use function core\object_to_array;

class RoleAction extends Action
{
    public static function index(Base $context , array $param = [])
    {
        $order = $param['order'] === '' ? [] : parse_order($param['order'] , '|');
        $size = $param['size'] === '' ? my_config('app.limit') : $param['size'];
        $res = RoleModel::index($param , $order , $size);
        $res = RoleHandler::handlePaginator($res);
        return self::success('' , $res);
    }

    public static function update(Base $context , $id , array $param = [])
    {
        $validator = Validator::make($param , [
            'name' => 'required' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , $validator->errors());
        }
        $role = RoleModel::find($id);
        if (empty($role)) {
            return self::error('角色不存在' , '' , 404);
        }
        $param['weight'] = $param['weight'] === '' ? 0 : $param['weight'];
        RoleModel::updateById($role->id , array_unit($param , [
            'name' ,
            'weight' ,
        ]));
        return self::success('操作成功');
    }

    public static function store(Base $context , array $param = [])
    {
        $validator = Validator::make($param , [
            'name' => 'required' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , $validator->errors());
        }
        $param['weight'] = $param['weight'] === '' ? 0 : $param['weight'];
        $id = RoleModel::insertGetId(array_unit($param , [
            'name' ,
            'weight' ,
        ]));
        return self::success('' , $id);
    }

    public static function show(Base $context , $id , array $param = [])
    {
        $role = RoleModel::find($id);
        if (empty($role)) {
            return self::error('角色不存在' , '' , 404);
        }
        $role = RoleModel::handle($role);
        return self::success('' , $role);
    }

    public static function destroy(Base $context , $id , array $param = [])
    {
        try {
            DB::beginTransaction();
            RoleModel::destroy($id);
            RolePermissionPivot::delByRoleId($id);
            DB::commit();
            return self::success('操作成功');
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function destroyAll(Base $context , array $ids , array $param = [])
    {
        try {
            DB::beginTransaction();
            RoleModel::destroy($ids);
            RolePermissionPivot::delByRoleIds($ids);
            DB::commit();
            return self::success('操作成功');
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function allocatePermission(Base $context , $id , array $param = [])
    {
        $validator = Validator::make($param , [
            'permission' => 'required' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , $validator->errors());
        }
        $role = RoleModel::find($id);
        if (empty($role)) {
            return self::error('角色不存在' , '' , 404);
        }
        $permission = json_decode($param['permission'] , true);
        try {
            DB::beginTransaction();
            RolePermissionPivot::delByRoleId($role->id);
            foreach ($permission as $v)
            {
                RolePermissionPivot::insert([
                    'role_id'               => $role->id ,
                    'admin_permission_id'   => $v
                ]);
            }
            DB::commit();
            return self::success('操作成功');
        } catch(Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public static function permission(Base $context , $id , array $param = [])
    {
        $role = RoleModel::find($id);
        if (empty($role)) {
            return self::error('角色不存在' , '' , 404);
        }
        $admin_permission_ids = RolePermissionPivot::getPermissionIdsByRoleId($role->id);
        $permission = AdminPermissionModel::find($admin_permission_ids);
        $permission = AdminPermissionHandler::handleAll($permission);
        return self::success('' , $permission);
    }

    public static function all(Base $context , array $param = [])
    {
        $res = RoleModel::get();
        $res = RoleHandler::handleAll($res);
        return self::success('' , $res);
    }
}
