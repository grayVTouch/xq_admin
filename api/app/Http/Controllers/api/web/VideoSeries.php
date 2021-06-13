<?php


namespace App\Http\Controllers\api\web;


use App\Customize\api\web\action\VideoSeriesAction;
use function api\web\error;
use function api\web\success;

class VideoSeries extends Base
{
    public function index()
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $param['order'] = $param['order'] ?? '';
        $param['value'] = $param['value'] ?? '';
        $res = VideoSeriesAction::index($this , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'], $res['code']);
        }
        return success($res['message'] , $res['data']);
    }
}
