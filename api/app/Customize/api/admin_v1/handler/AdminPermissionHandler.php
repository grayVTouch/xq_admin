<?php


namespace App\Customize\api\admin_v1\handler;


use App\Customize\api\admin_v1\model\AdminPermissionModel;
use Illuminate\Support\Facades\Storage;
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
        $model->__b_ico__ = empty($model->b_ico) ? '' : Storage::url($model->b_ico);
        $model->__s_ico__ = empty($model->s_ico) ? '' : Storage::url($model->s_ico);
        return $model;
    }


}
