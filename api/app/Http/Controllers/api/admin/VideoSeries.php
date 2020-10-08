<?php


namespace App\Http\Controllers\api\admin;


use App\Customize\api\admin\action\VideoSeriesAction;
use function api\admin\error;
use function api\admin\success;

class VideoSeries extends Base
{
    public function index()
    {
        $param = $this->request->query();

        $param['id']           = $param['id'] ?? '';
        $param['name']         = $param['name'] ?? '';
        $param['module_id']    = $param['module_id'] ?? '';
        $param['order'] = $param['order'] ?? '';
        $param['limit'] = $param['limit'] ?? '';

        $res = VideoSeriesAction::index($this , $param);

        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }

        return success($res['message'] , $res['data']);
    }

    public function update($id)
    {
        $param = $this->request->post();
        $param['name']          = $param['name'] ?? '';
        $param['weight']        = $param['weight'] ?? '';
        $param['module_id']     = $param['module_id'] ?? '';
        $param['user_id']       = $param['user_id'] ?? '';
        $param['status']        = $param['status'] ?? '';
        $param['fail_reason']   = $param['fail_reason'] ?? '';
        $res = VideoSeriesAction::update($this , $id ,$param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function store()
    {
        $param = $this->request->post();
        $param['name']          = $param['name'] ?? '';
        $param['weight']        = $param['weight'] ?? '';
        $param['module_id']     = $param['module_id'] ?? '';
        $param['user_id']       = $param['user_id'] ?? '';
        $param['status']        = $param['status'] ?? '';
        $param['fail_reason']   = $param['fail_reason'] ?? '';
        $res = VideoSeriesAction::store($this ,$param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function show($id)
    {
        $res = VideoSeriesAction::show($this , $id);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function destroy($id)
    {
        $res = VideoSeriesAction::destroy($this , $id);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function destroyAll()
    {
        $ids = $this->request->input('ids' , '[]');
        $ids = json_decode($ids , true);
        $res = VideoSeriesAction::destroyAll($this , $ids);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function search()
    {
        $param = $this->request->query();
        $param['value'] = $param['value'] ?? '';
        $param['limit'] = $param['limit'] ?? '';
        $param['module_id'] = $param['module_id'] ?? '';
        $res = VideoSeriesAction::search($this , $param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }
}
