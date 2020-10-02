<?php


namespace App\Customize\api\admin\handler;


use App\Customize\api\admin\model\AdminModel;
use App\Customize\api\admin\model\AdminPermissionModel;
use App\Customize\api\admin\model\RoleModel;
use App\Customize\api\admin\model\RolePermissionPivot;
use Core\Lib\Category;
use App\Customize\api\admin\util\FileUtil;
use stdClass;
use function api\admin\get_value;
use function core\convert_obj;
use function core\obj_to_array;

class AdminHandler extends Handler
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

        $model->__sex__ = get_value('business.sex_for_user' , $model->sex);
        $model->__is_root__ = get_value('business.bool_for_int' , $model->is_root);
        return $model;
    }
}
