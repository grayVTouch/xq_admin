<?php


namespace App\Customize\api\admin_v1\handler;


use App\Customize\api\admin_v1\model\AdminModel;
use App\Customize\api\admin_v1\model\AdminPermissionModel;
use App\Customize\api\admin_v1\model\RoleModel;
use Core\Lib\Category;
use stdClass;
use function core\convert_obj;
use function core\obj_to_array;

class AdminHandler
{
    public static function handle(?AdminModel $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $model = convert_obj($model);
        $role = RoleModel::find($model->role_id);
        $role = RoleHandler::handle($role);
        $model->role = $role;
        if ($model->is_root) {
            $permission = AdminPermissionModel::getAll();
        } else {
            $permission = AdminPermissionModel::getByRoleId($model->role_id);
        }
        $permission = AdminPermissionHandler::handleAll($permission);
        $permission = obj_to_array($permission);
        $permission = Category::childrens(0 , $permission , [
            'id'    => 'id' ,
            'p_id'  => 'p_id' ,
        ] , false , false);
        $model->permission = $permission;
        return $model;
    }
}
