<?php


namespace App\Http\Controllers\api\admin_v1;


use App\Customize\api\admin_v1\action\ImageSubjectAction;
use function api\admin_v1\error;
use function api\admin_v1\success;

class ImageSubject extends Base
{
    public function index()
    {
        $param = $this->request->query();
        $param['name'] = $param['name'] ?? '';
        $param['order'] = $param['order'] ?? '';
        $param['limit'] = $param['limit'] ?? '';
        $res = ImageSubjectAction::index($this , $param);
        if ($res['code'] !== 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function update($id)
    {
        $param = $this->request->post();
        $param['name']          = $param['name'] ?? '';
        $param['user_id']       = $param['user_id'] ?? '';
        $param['module_id']     = $param['module_id'] ?? '';
        $param['category_id']   = $param['category_id'] ?? '';
        $param['type']          = $param['type'] ?? '';
        $param['subject_id']    = $param['subject_id'] ?? '';
        $param['tag']           = $param['tag'] ?? '';
        $param['thumb']         = $param['thumb'] ?? '';
        $param['description']   = $param['description'] ?? '';
        $param['weight']        = $param['weight'] ?? '';
        $param['view_count']    = $param['view_count'] ?? '';
        $param['praise_count']  = $param['praise_count'] ?? '';
        $param['status']        = $param['status'] ?? '';
        $param['fail_reason']   = $param['fail_reason'] ?? '';
        $param['images']   = $param['images'] ?? '';
        $param['create_time']   = $param['create_time'] ?? '';
        $res = ImageSubjectAction::update($this , $id ,$param);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function store()
    {
        $param = $this->request->post();
        $param['name']          = $param['name'] ?? '';
        $param['user_id']       = $param['user_id'] ?? '';
        $param['module_id']     = $param['module_id'] ?? '';
        $param['category_id']   = $param['category_id'] ?? '';
        $param['type']          = $param['type'] ?? '';
        $param['subject_id']    = $param['subject_id'] ?? '';
        $param['tag']           = $param['tag'] ?? '';
        $param['thumb']         = $param['thumb'] ?? '';
        $param['description']   = $param['description'] ?? '';
        $param['weight']        = $param['weight'] ?? '';
        $param['view_count']    = $param['view_count'] ?? '';
        $param['praise_count']  = $param['praise_count'] ?? '';
        $param['status']        = $param['status'] ?? '';
        $param['fail_reason']   = $param['fail_reason'] ?? '';
        $param['images']   = $param['images'] ?? '';
        $param['create_time']   = $param['create_time'] ?? '';
        $res = ImageSubjectAction::store($this ,$param);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function show($id)
    {
        $res = ImageSubjectAction::show($this , $id);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function destroy($id)
    {
        $res = ImageSubjectAction::destroy($this , $id);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function destroyAll()
    {
        $ids = $this->request->input('ids' , '[]');
        $ids = json_decode($ids , true);
        $res = ImageSubjectAction::destroyAll($this , $ids);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function destroyImages()
    {
        $ids = $this->request->input('ids' , '[]');
        $ids = json_decode($ids , true);
        $res = ImageSubjectAction::destroyImages($this , $ids);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }
}
