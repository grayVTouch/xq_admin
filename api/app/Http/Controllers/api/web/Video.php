<?php


namespace App\Http\Controllers\api\web;


use App\Customize\api\web\action\VideoAction;
use function api\web\error;
use function api\web\success;

class Video extends Base
{
    public function incrementViewCount(int $id)
    {
        $param = $this->request->post();
        $param['module_id'] = $param['module_id'] ?? '';
        $res = VideoAction::incrementViewCount($this , $id , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'], $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function incrementPlayCount(int $id)
    {
        $param = $this->request->post();
        $param['module_id'] = $param['module_id'] ?? '';
        $res = VideoAction::incrementPlayCount($this , $id , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'], $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function praiseHandle(int $id)
    {
        $param = $this->request->post();
        $param['module_id'] = $param['module_id'] ?? '';
        $res = VideoAction::praiseHandle($this , $id , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'], $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function record(int $id)
    {
        $param = $this->request->post();
        $param['module_id'] = $param['module_id'] ?? '';
        $param['index'] = $param['index'] ?? '';
        $param['played_duration'] = $param['played_duration'] ?? '';
        $param['definition'] = $param['definition'] ?? '';
        $param['subtitle'] = $param['subtitle'] ?? '';
        $res = VideoAction::record($this , $id , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'], $res['code']);
        }
        return success($res['message'] , $res['data']);
    }
}
