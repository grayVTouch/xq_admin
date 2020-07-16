<?php


namespace App\Http\Controllers\api\web_v1;


use App\Customize\api\web_v1\action\SubjectAction;
use function api\web_v1\error;
use function api\web_v1\success;

class Subject extends Base
{
    public function show($id)
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $res = SubjectAction::show($this , $id , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'], $res['code']);
        }
        return success($res['message'] , $res['data']);
    }
}
