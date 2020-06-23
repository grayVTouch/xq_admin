<?php


namespace App\Http\Controllers\api\web_v1;


use App\Customize\api\web_v1\action\ImageSubjectAction;
use function api\web_v1\error;
use function api\web_v1\success;

class ImageSubject extends Base
{
    public function getNewestByLimit($limit)
    {
        $res = ImageSubjectAction::getNewestByLimit($this , $limit);
        if ($res['code'] !== 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }
}
