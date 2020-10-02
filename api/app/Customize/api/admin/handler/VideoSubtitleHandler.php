<?php


namespace App\Customize\api\admin\handler;


use App\Customize\api\admin\model\ModuleModel;
use App\Customize\api\admin\model\VideoSrcModel;
use App\Customize\api\admin\model\VideoSubtitleModel;
use App\Customize\api\admin\util\FileUtil;
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


        return $res;
    }

}
