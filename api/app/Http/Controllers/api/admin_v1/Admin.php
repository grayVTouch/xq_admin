<?php


namespace App\Http\Controllers\api\admin_v1;


use App\Customize\api\admin_v1\action\AdminAction;
use function api\admin_v1\error;
use function api\admin_v1\success;

class Admin extends Base
{
    public function info()
    {
        $res = AdminAction::info($this , []);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function search()
    {
        $value = $this->request->get('value' , '');
        $res = AdminAction::search($this , $value);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function index()
    {
        $param = $this->request->query();
        $param['username'] = $param['username'] ?? '';
        $param['order'] = $param['order'] ?? '';
        $param['limit'] = $param['limit'] ?? '';
        $res = AdminAction::index($this , $param);
        if ($res['code'] !== 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function update($id)
    {
        $param = $this->request->post();
        $param['username']      = $param['username'] ?? '';
        $param['password']  = $param['password'] ?? '';
        $param['sex']       = $param['sex'] ?? '';
        $param['birthday']  = $param['birthday'] ?? '';
        $param['avatar']    = $param['avatar'] ?? '';
        $param['phone']     = $param['phone'] ?? '';
        $param['email']     = $param['email'] ?? '';
        $res = AdminAction::update($this , $id ,$param);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function store()
    {
        $param = $this->request->post();
        $param['username']      = $param['username'] ?? '';
        $param['password']  = $param['password'] ?? '';
        $param['sex']       = $param['sex'] ?? '';
        $param['birthday']  = $param['birthday'] ?? '';
        $param['avatar']    = $param['avatar'] ?? '';
        $param['phone']     = $param['phone'] ?? '';
        $param['email']     = $param['email'] ?? '';
        $res = AdminAction::store($this ,$param);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function show($id)
    {
        $res = AdminAction::show($this , $id);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function destroy($id)
    {
        $res = AdminAction::destroy($this , $id);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function destroyAll()
    {
        $ids = $this->request->input('ids' , '[]');
        $ids = json_decode($ids , true);
        $res = AdminAction::destroyAll($this , $ids);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }
}
