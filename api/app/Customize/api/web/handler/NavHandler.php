<?php


namespace App\Customize\api\web\handler;


use App\Customize\api\web\model\Model;
use App\Customize\api\web\model\NavModel;
use stdClass;
use Traversable;
use function core\convert_object;

class NavHandler extends Handler
{
    public static function handle(?Model $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_object($model);

        return $res;
    }

    public static function parent($model): void
    {
        if (empty($model)) {
            return ;
        }
        $nav = $model->p_id ? NavModel::find($model->p_id) : null;
        $nav = self::handle($nav);
        $model->nav = $nav;
    }

}
