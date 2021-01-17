<?php


namespace App\Customize\api\web\handler;


use App\Customize\api\web\model\Model;
use App\Customize\api\web\model\NavModel;
use stdClass;
use Traversable;
use function core\convert_object;

class NavHandler extends Handler
{
    public static function handle(?Model $model , array $with = [] , bool $deep = true): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_object($model);
        if ($deep) {
            $nav = $res->p_id ? NavModel::find($res->p_id) : null;
            $nav = self::handle($nav , $with , false);
        } else {
            $nav = null;
        }
        $res->nav = $nav;

//        $res->__platform__ = get_config_key_mapping_value('business.platform' , $res->platform);

        return $res;
    }
}
