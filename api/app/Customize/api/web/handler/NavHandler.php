<?php


namespace App\Customize\api\web\handler;


use App\Customize\api\web\model\NavModel;
use stdClass;
use Traversable;
use function api\web\get_value;
use function core\convert_object;

class NavHandler extends Handler
{
    public static function handle(?NavModel $model , bool $deep = true): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_object($model);
        if ($deep) {
            $nav = $res->p_id ? NavModel::find($res->p_id) : null;
            $nav = self::handle($nav , false);
        } else {
            $nav = null;
        }
        $res->nav = $nav;

//        $res->__platform__ = get_config_key_mapping_value('business.platform' , $res->platform);

        return $res;
    }
}
