<?php


namespace App\Customize\api\admin_v1\handler;


use App\Customize\api\admin_v1\model\ImageAtPositionModel;
use App\Customize\api\admin_v1\model\PositionModel;
use Illuminate\Support\Facades\Storage;
use stdClass;
use function core\convert_obj;

class ImageAtPositionHandler extends Handler
{
    public static function handle(?ImageAtPositionModel $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_obj($model);
        $position = PositionModel::find($res->position_id);
        $position = PositionHandler::handle($position);

        $res->position = $position;
        $res->__path__ = empty($res->path) ? '' : Storage::url($res->path);
        return $res;
    }

}
