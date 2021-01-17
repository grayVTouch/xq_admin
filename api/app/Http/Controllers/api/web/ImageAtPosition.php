<?php


namespace App\Http\Controllers\api\web;


use App\Customize\api\web\action\ImageAtPositionAction;
use function api\web\error;
use function api\web\success;

class ImageAtPosition extends Base
{
    public function home()
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $res = ImageAtPositionAction::imageAtPosition($this , 'home' , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function imageProject()
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $res = ImageAtPositionAction::imageAtPosition($this , 'image_project' , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }
}
