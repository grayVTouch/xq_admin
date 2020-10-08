<?php


namespace App\Http\Controllers\api\admin;


use App\Customize\api\admin\action\VideoCompanyAction;
use function api\admin\error;
use function api\admin\success;

class VideoCompany extends Base
{
    public function index()
    {
        $param = $this->request->query();

        $param['id']           = $param['id'] ?? '';
        $param['name']         = $param['name'] ?? '';
        $param['module_id']    = $param['module_id'] ?? '';
        $param['country_id']   = $param['country_id'] ?? '';
        $param['order']        = $param['order'] ?? '';
        $param['limit']        = $param['limit'] ?? '';

        $res = VideoCompanyAction::index($this , $param);

        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }

        return success($res['message'] , $res['data']);
    }

    public function update($id)
    {
        $param = $this->request->post();
        $param['name']          = $param['name'] ?? '';
        $param['thumb']        = $param['thumb'] ?? '';
        $param['description']  = $param['description'] ?? '';
        $param['country_id']        = $param['country_id'] ?? '';
        $param['weight']        = $param['weight'] ?? '';
        $param['module_id']        = $param['module_id'] ?? '';
        $param['user_id']       = $param['user_id'] ?? '';
        $param['status']        = $param['status'] ?? '';
        $param['fail_reason']   = $param['fail_reason'] ?? '';
        $res = VideoCompanyAction::update($this , $id ,$param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function store()
    {
        $param = $this->request->post();
        $param['name']          = $param['name'] ?? '';
        $param['thumb']         = $param['thumb'] ?? '';
        $param['description']   = $param['description'] ?? '';
        $param['country_id']    = $param['country_id'] ?? '';
        $param['weight']        = $param['weight'] ?? '';
        $param['module_id']     = $param['module_id'] ?? '';
        $param['user_id']       = $param['user_id'] ?? '';
        $param['status']        = $param['status'] ?? '';
        $param['fail_reason']   = $param['fail_reason'] ?? '';
        $res = VideoCompanyAction::store($this ,$param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function show($id)
    {
        $res = VideoCompanyAction::show($this , $id);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function destroy($id)
    {
        $res = VideoCompanyAction::destroy($this , $id);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function destroyAll()
    {
        $ids = $this->request->input('ids' , '[]');
        $ids = json_decode($ids , true);
        $res = VideoCompanyAction::destroyAll($this , $ids);
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
        $res = VideoCompanyAction::search($this , $param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }
}
