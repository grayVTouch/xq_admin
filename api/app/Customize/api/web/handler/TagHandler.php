<?php


namespace App\Customize\api\web\handler;


use App\Customize\api\web\model\TagModel;
use stdClass;
use function core\convert_obj;

class TagHandler extends Handler
{
    public static function handle(?TagModel $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_obj($model);
        return $res;
    }

}
