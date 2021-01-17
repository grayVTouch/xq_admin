<?php


namespace App\Http\Controllers\api\admin;


use App\Customize\api\admin\action\NavAction;
use function api\admin\error;
use function api\admin\success;

class Nav extends Base
{
    public function index()
    {
        $param = $this->request->query();
        $res = NavAction::index($this , $param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function search()
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $param['type'] = $param['type'] ?? '';
        $res = NavAction::search($this , $param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function localUpdate($id)
    {
        $param = $this->request->post();
        $param['p_id']          = $param['p_id'] ?? '';
        $param['name']          = $param['name'] ?? '';
        $param['value']         = $param['value'] ?? '';
        $param['platform']      = $param['platform'] ?? '';
        $param['enable']        = $param['enable'] ?? '';
        $param['is_menu']       = $param['is_menu'] ?? '';
        $param['weight']        = $param['weight'] ?? '';
        $param['module_id']     = $param['module_id'] ?? '';
        $res = NavAction::localUpdate($this , $id ,$param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function update($id)
    {
        $param = $this->request->post();
        $param['p_id']          = $param['p_id'] ?? '';
        $param['name']          = $param['name'] ?? '';
        $param['value']         = $param['value'] ?? '';
        $param['platform']      = $param['platform'] ?? '';
        $param['enable']        = $param['enable'] ?? '';
        $param['is_menu']       = $param['is_menu'] ?? '';
        $param['weight']        = $param['weight'] ?? '';
        $param['module_id']     = $param['module_id'] ?? '';
        $res = NavAction::update($this , $id ,$param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function store()
    {
        $param = $this->request->post();
        $param['p_id']        = $param['p_id'] ?? '';
        $param['name']        = $param['name'] ?? '';
        $param['value'] = $param['value'] ?? '';
        $param['enable']    = $param['enable'] ?? '';
        $param['is_menu']    = $param['is_menu'] ?? '';
        $param['weight']    = $param['weight'] ?? '';
        $param['module_id']        = $param['module_id'] ?? '';
        $param['platform']        = $param['platform'] ?? '';
        $res = NavAction::store($this ,$param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function show($id)
    {
        $res = NavAction::show($this , $id);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function destroy($id)
    {
        $res = NavAction::destroy($this , $id);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function destroyAll()
    {
        $ids = $this->request->input('ids' , '[]');
        $ids = json_decode($ids , true);
        $res = NavAction::destroyAll($this , $ids);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }
}
