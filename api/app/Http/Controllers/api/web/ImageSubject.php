<?php


namespace App\Http\Controllers\api\web;


use App\Customize\api\web\action\ImageSubjectAction;
use function api\web\error;
use function api\web\success;

class ImageSubject extends Base
{
    public function show($id)
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $res = ImageSubjectAction::show($this , $id , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'], $res['code']);
        }
        return success($res['message'] , $res['data']);
    }
}
