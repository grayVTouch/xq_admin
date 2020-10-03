<?php


namespace App\Customize\api\admin\handler;


use App\Customize\api\admin\model\AdminModel;
use App\Customize\api\admin\model\AdminPermissionModel;
use App\Customize\api\admin\model\RoleModel;
use App\Customize\api\admin\model\RolePermissionPivot;
use App\Customize\api\admin\model\Model;
use Core\Lib\Category;
use App\Customize\api\admin\util\FileUtil;
use stdClass;
use function api\admin\get_config_key_mapping_value;

use function core\convert_object;
use function core\obj_to_array;

class AdminHandler extends Handler
{
    public static function handle(?Model $model , array $with = []): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $model = convert_object($model);
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

        $model->__sex__     = get_config_key_mapping_value('business.sex_for_user' , $model->sex);
        $model->__is_root__ = get_config_key_mapping_value('business.bool_for_int' , $model->is_root);
        return $model;
    }
}
