<?php


namespace App\Customize\api\web_v1\handler;


use App\Customize\api\web_v1\model\VideoSrcModel;
use App\Customize\api\web_v1\util\FileUtil;
use stdClass;
use function core\convert_obj;

class VideoSrcHandler extends Handler
{
    public static function handle(?VideoSrcModel $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_obj($model);
        $res->__src__ = empty($res->src) ? '' : FileUtil::url($res->src);
        return $res;
    }

}
