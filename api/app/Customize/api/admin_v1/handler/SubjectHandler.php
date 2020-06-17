<?php


namespace App\Customize\api\admin_v1\handler;


use App\Customize\api\admin_v1\model\SubjectModel;
use Illuminate\Support\Facades\Storage;
use stdClass;
use function core\convert_obj;

class SubjectHandler extends Handler
{
    public static function handle(?SubjectModel $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_obj($model);
        $res->__attr__ = empty($res->attr) ? [] : json_decode($res->attr , true);
        $res->__thumb__ = empty($res->thumb) ? '' : Storage::url($res->thumb);
        return $res;
    }

}
