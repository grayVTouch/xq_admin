<?php


namespace App\Customize\api\web_v1\handler;


use App\Customize\api\web_v1\model\ImageModel;
use Illuminate\Support\Facades\Storage;
use stdClass;
use function core\convert_obj;

class ImageHandler extends Handler
{
    public static function handle(?ImageModel $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_obj($model);
        $res->__path__ = empty($res->path) ? '' : Storage::url($res->path);
        return $res;
    }

}
