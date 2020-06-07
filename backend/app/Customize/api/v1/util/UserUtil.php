<?php


namespace App\Customize\api\v1\util;


use App\Customize\api\v1\model\AdminPermissionModel;
use App\Customize\api\v1\model\RoleModel;
use Core\Lib\Category;
use function core\obj_to_array;

class UserUtil extends Util
{
    public static function handle($user)
    {
        if (empty($user)) {
            return ;
        }
        $user->role = RoleModel::findById($user->role_id);
        if ($user->is_root) {
            $permission = AdminPermissionModel::getAll();
        } else {
            $permission = AdminPermissionModel::getByRoleId($user->role_id);
        }
        $permission = obj_to_array($permission);
        $permission = Category::childrens(0 , $permission , [
            'id'    => 'id' ,
            'p_id'  => 'p_id' ,
        ] , false , false);
        $user->permission = $permission;
    }
}
