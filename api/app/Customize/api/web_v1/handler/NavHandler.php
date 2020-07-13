<?php


namespace App\Customize\api\web_v1\handler;


use App\Customize\api\web_v1\model\NavModel;
use stdClass;
use Traversable;
use function core\convert_obj;

class NavHandler extends Handler
{
    public static function handle(?NavModel $model , bool $deep = true): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $model = convert_obj($model);
        if ($deep) {
            $nav = $model->p_id ? NavModel::find($model->p_id) : null;
            $nav = self::handle($nav , false);
        } else {
            $nav = null;
        }
        $model->nav = $nav;
        return $model;
    }
}