<?php


namespace App\Http\Controllers\api\web_v1;


use App\Customize\api\web_v1\action\ImageAtPositionAction;
use function api\web_v1\error;
use function api\web_v1\success;

class ImageAtPosition extends Base
{
    public function homeSlideshow()
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $res = ImageAtPositionAction::imageAtPosition($this , 'home_slideshow' , $param);
        if ($res['code'] !== 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function imageSubject()
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $res = ImageAtPositionAction::imageAtPosition($this , 'image_subject' , $param);
        if ($res['code'] !== 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }
}
