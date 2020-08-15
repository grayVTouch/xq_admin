<?php


namespace App\Customize\api\admin_v1\handler;


use App\Customize\api\admin_v1\model\ModuleModel;
use App\Customize\api\admin_v1\model\VideoSrcModel;
use App\Customize\api\admin_v1\model\VideoSubtitleModel;
use App\Customize\api\admin_v1\util\FileUtil;
use stdClass;
use function core\convert_obj;

class VideoSubtitleHandler extends Handler
{
    public static function handle(?VideoSubtitleModel $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_obj($model);

        $res->__src__ = empty($res->src) ? '' : FileUtil::url($res->src);
        return $res;
    }

}
