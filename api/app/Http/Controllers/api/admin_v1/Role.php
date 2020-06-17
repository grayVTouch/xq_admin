<?php


namespace App\Http\Controllers\api\admin_v1;


use App\Customize\api\admin_v1\action\RoleAction;
use function api\admin_v1\error;
use function api\admin_v1\success;

class Role extends Base
{
    public function index()
    {
        $param = $this->request->query();
        $param['name'] = $param['name'] ?? '';
        $param['order'] = $param['order'] ?? '';
        $param['limit'] = $param['limit'] ?? '';
        $res = RoleAction::index($this , $param);
        if ($res['code'] !== 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function update($id)
    {
        $param = $this->request->post();
        $param['name']     = $param['name'] ?? '';
        $param['weight']        = $param['weight'] ?? '';
        $res = RoleAction::update($this , $id ,$param);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function store()
    {
        $param = $this->request->post();
        $param['name']     = $param['name'] ?? '';
        $param['weight']        = $param['weight'] ?? '';
        $res = RoleAction::store($this ,$param);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function show($id)
    {
        $res = RoleAction::show($this , $id);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function destroy($id)
    {
        $res = RoleAction::destroy($this , $id);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function destroyAll()
    {
        $ids = $this->request->input('ids' , '[]');
        $ids = json_decode($ids , true);
        $res = RoleAction::destroyAll($this , $ids);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function allocatePermission($id)
    {
        $param = $this->request->post();
        $param['permission'] = $param['permission'] ?? '';
        $res = RoleAction::allocatePermission($this , $id , $param);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function permission($id)
    {
        $res = RoleAction::permission($this , $id);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function all()
    {
        $res = RoleAction::all($this);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }
}
