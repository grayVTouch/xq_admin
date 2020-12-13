<?php


namespace App\Http\Controllers\api\admin;


use App\Customize\api\admin\action\CategoryAction;
use function api\admin\error;
use function api\admin\success;

class Category extends Base
{
    public function index()
    {
        $param = $this->request->query();
        $res = CategoryAction::index($this , $param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function search()
    {
        $param = $this->request->query();
        $param['module_id'] = $param['module_id'] ?? '';
        $param['type']      = $param['type'] ?? '';
        $res        = CategoryAction::search($this , $param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }

        return success($res['message'] , $res['data']);
    }

    public function allExcludeSelfAndChildren($id)
    {
        $res = CategoryAction::allExcludeSelfAndChildren($this , $id);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function localUpdate($id)
    {
        $param = $this->request->post();
        $param['p_id']        = $param['p_id'] ?? '';
        $param['name']        = $param['name'] ?? '';
        $param['description'] = $param['description'] ?? '';
        $param['is_enabled']    = $param['is_enabled'] ?? '';
        $param['weight']    = $param['weight'] ?? '';
        $param['module_id']        = $param['module_id'] ?? '';
        $res = CategoryAction::localUpdate($this , $id ,$param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function update($id)
    {
        $param = $this->request->post();
        $param['p_id']        = $param['p_id'] ?? '';
        $param['name']        = $param['name'] ?? '';
        $param['description'] = $param['description'] ?? '';
        $param['is_enabled']    = $param['is_enabled'] ?? '';
        $param['weight']    = $param['weight'] ?? '';
        $param['module_id']        = $param['module_id'] ?? '';
        $res = CategoryAction::update($this , $id ,$param);
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
        $param['description'] = $param['description'] ?? '';
        $param['is_enabled']    = $param['is_enabled'] ?? '';
        $param['weight']    = $param['weight'] ?? '';
        $param['module_id']        = $param['module_id'] ?? '';
        $res = CategoryAction::store($this ,$param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function show($id)
    {
        $res = CategoryAction::show($this , $id);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function destroy($id)
    {
        $res = CategoryAction::destroy($this , $id);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function destroyAll()
    {
        $ids = $this->request->input('ids' , '[]');
        $ids = json_decode($ids , true);
        $res = CategoryAction::destroyAll($this , $ids);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }
}
