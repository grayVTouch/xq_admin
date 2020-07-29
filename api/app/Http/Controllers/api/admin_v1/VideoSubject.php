<?php


namespace App\Http\Controllers\api\admin_v1;


use App\Customize\api\admin_v1\action\VideoSubjectAction;
use function api\admin_v1\error;
use function api\admin_v1\success;

class VideoSubject extends Base
{
    public function index()
    {
        $param = $this->request->query();
        $param['name'] = $param['name'] ?? '';
        $param['order'] = $param['order'] ?? '';
        $param['limit'] = $param['limit'] ?? '';
        $res = VideoSubjectAction::index($this , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function update($id)
    {
        $param = $this->request->post();
        $param['name']          = $param['name'] ?? '';
        $param['description']   = $param['description'] ?? '';
        $param['thumb']         = $param['thumb'] ?? '';
        $param['attr']          = $param['attr'] ?? '';
        $param['weight']        = $param['weight'] ?? '';
        $param['module_id']        = $param['module_id'] ?? '';
        $res = VideoSubjectAction::update($this , $id ,$param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function store()
    {
        $param = $this->request->post();
        $param['name']          = $param['name'] ?? '';
        $param['description']   = $param['description'] ?? '';
        $param['thumb']         = $param['thumb'] ?? '';
        $param['attr']          = $param['attr'] ?? '';
        $param['weight']        = $param['weight'] ?? '';
        $param['module_id']        = $param['module_id'] ?? '';
        $res = VideoSubjectAction::store($this ,$param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function show($id)
    {
        $res = VideoSubjectAction::show($this , $id);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function destroy($id)
    {
        $res = VideoSubjectAction::destroy($this , $id);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function destroyAll()
    {
        $ids = $this->request->input('ids' , '[]');
        $ids = json_decode($ids , true);
        $res = VideoSubjectAction::destroyAll($this , $ids);
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
        $res = VideoSubjectAction::search($this , $param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }
}
