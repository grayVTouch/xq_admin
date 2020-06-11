<?php


namespace App\Customize\api\v1\handler;


use App\Customize\api\v1\model\AdminPermissionModel;
use stdClass;
use Traversable;
use function core\convert_obj;

class AdminPermissionHandler
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
        return $model;
    }

    public static function handleAll(Traversable $list) :array
    {
        $res = [];
        foreach ($list as $v)
        {
            $res[] = self::handle($v);
        }
        return $res;
    }
}
