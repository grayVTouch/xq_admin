<?php


namespace App\Http\Controllers\api\admin_v1;


use App\Customize\api\admin_v1\action\CategoryAction;
use function api\admin_v1\error;
use function api\admin_v1\success;

class Category extends Base
{
    public function index()
    {
        $res = CategoryAction::index($this);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function allExcludeSelfAndChildren($id)
    {
        $res = CategoryAction::allExcludeSelfAndChildren($this , $id);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function localUpdate($id)
    {
        $param = $this->request->post();
        $param['p_id']        = $param['p_id'] ?? '';
        $param['name']        = $param['name'] ?? '';
        $param['description'] = $param['description'] ?? '';
        $param['enable']    = $param['enable'] ?? '';
        $param['weight']    = $param['weight'] ?? '';
        $res = CategoryAction::localUpdate($this , $id ,$param);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function update($id)
    {
        $param = $this->request->post();
        $param['p_id']        = $param['p_id'] ?? '';
        $param['name']        = $param['name'] ?? '';
        $param['description'] = $param['description'] ?? '';
        $param['enable']    = $param['enable'] ?? '';
        $param['weight']    = $param['weight'] ?? '';
        $res = CategoryAction::update($this , $id ,$param);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function store()
    {
        $param = $this->request->post();
        $param['p_id']        = $param['p_id'] ?? '';
        $param['name']        = $param['name'] ?? '';
        $param['description'] = $param['description'] ?? '';
        $param['enable']    = $param['enable'] ?? '';
        $param['weight']    = $param['weight'] ?? '';
        $res = CategoryAction::store($this ,$param);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function show($id)
    {
        $res = CategoryAction::show($this , $id);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function destroy($id)
    {
        $res = CategoryAction::destroy($this , $id);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function destroyAll()
    {
        $ids = $this->request->input('ids' , '[]');
        $ids = json_decode($ids , true);
        $res = CategoryAction::destroyAll($this , $ids);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }
}