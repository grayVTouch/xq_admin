<?php


namespace App\Customize\api\admin_v1\handler;


use App\Customize\api\admin_v1\model\AdminPermissionModel;
use stdClass;
use Traversable;
use function core\convert_obj;

class AdminPermissionHandler extends Handler
{
    public static function handle(?AdminPermissionModel $model , bool $deep = true): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $model = convert_obj($model);
        if ($deep) {
            $permission = $model->p_id ? AdminPermissionModel::find($model->p_id) : null;
            $permission = self::handle($permission , false);
        } else {
            $permission = null;
        }
        $model->permission = $permission;
        $model->__s_ico__ = $model->s_ico ? asset($model->s_ico) : '';
        $model->__b_ico__ = $model->b_ico ? asset($model->b_ico) : '';
        return $model;
    }


}
