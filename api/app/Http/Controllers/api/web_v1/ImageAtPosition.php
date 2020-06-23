<?php


namespace App\Http\Controllers\api\web_v1;


use App\Customize\api\web_v1\action\ImageAtPositionAction;
use function api\web_v1\error;
use function api\web_v1\success;

class ImageAtPosition extends Base
{
    public function homeSlideshow()
    {
        $res = ImageAtPositionAction::imageAtPosition($this , 'home_slideshow');
        if ($res['code'] !== 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }
}
