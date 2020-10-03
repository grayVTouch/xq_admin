<?php


namespace App\Customize\api\admin\handler;

use App\Customize\api\admin\model\Model;
use stdClass;
use function api\admin\get_config_key_mapping_value;

use function core\convert_object;

class ModuleHandler extends Handler
{
    public static function handle(?Model $model , array $with = []): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_object($model);

        $res->__is_enabled__ = get_config_key_mapping_value('business.bool_for_int' , $res->is_enabled);

        return $res;
    }

}
