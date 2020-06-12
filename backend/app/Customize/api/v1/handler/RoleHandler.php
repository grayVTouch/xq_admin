<?php


namespace App\Customize\api\v1\handler;


use App\Customize\api\v1\model\RoleModel;
use stdClass;
use function core\convert_obj;

class RoleHandler extends Handler
{
    public static function handle(?RoleModel $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_obj($model);
        return $res;
    }

}
