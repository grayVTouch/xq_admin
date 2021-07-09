<?php


namespace App\Http\Controllers\api\admin;


use App\Customize\api\admin\action\ImageSubjectAction;
use function api\admin\error;
use function api\admin\success;

class ImageSubject extends Base
{
    public function index()
    {
        $param = $this->request->query();
        $param['name'] = $param['name'] ?? '';
        $param['module_id'] = $param['module_id'] ?? '';
        $param['order'] = $param['order'] ?? '';
        $param['size'] = $param['size'] ?? '';
        $res = ImageSubjectAction::index($this , $param);
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
        $param['module_id']     = $param['module_id'] ?? '';
        $param['user_id']       = $param['user_id'] ?? '';
        $param['status']        = $param['status'] ?? '';
        $param['fail_reason']   = $param['fail_reason'] ?? '';
        $res = ImageSubjectAction::update($this , $id ,$param);
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
        $param['module_id']     = $param['module_id'] ?? '';
        $param['user_id']       = $param['user_id'] ?? '';
        $param['status']        = $param['status'] ?? '';
        $param['fail_reason']   = $param['fail_reason'] ?? '';
        $res = ImageSubjectAction::store($this ,$param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function show($id)
    {
        $res = ImageSubjectAction::show($this , $id);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function destroy($id)
    {
        $res = ImageSubjectAction::destroy($this , $id);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function destroyAll()
    {
        $ids = $this->request->input('ids' , '[]');
        $ids = json_decode($ids , true);
        $res = ImageSubjectAction::destroyAll($this , $ids);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function search()
    {
        $param = $this->request->query();
        $param['value'] = $param['value'] ?? '';
        $param['size'] = $param['size'] ?? '';
        $param['module_id'] = $param['module_id'] ?? '';
        $res = ImageSubjectAction::search($this , $param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }
}
