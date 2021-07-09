<?php


namespace App\Http\Controllers\api\admin;


use App\Customize\api\admin\action\AdminAction;
use function api\admin\error;
use function api\admin\success;

class Admin extends Base
{
    public function info()
    {
        $res = AdminAction::info($this , []);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function search()
    {
        $value = $this->request->get('value' , '');
        $res = AdminAction::search($this , $value);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function index()
    {
        $param = $this->request->query();

        $param['username']  = $param['username'] ?? '';
        $param['sex']       = $param['sex'] ?? '';
        $param['phone']     = $param['phone'] ?? '';
        $param['email']     = $param['email'] ?? '';
        $param['role_id']   = $param['role_id'] ?? '';
        $param['is_root']   = $param['is_root'] ?? '';
        $param['order']     = $param['order'] ?? '';
        $param['size']     = $param['size'] ?? '';

        $res = AdminAction::index($this , $param);

        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }

        return success($res['message'] , $res['data']);
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
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
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
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function show($id)
    {
        $res = AdminAction::show($this , $id);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function destroy($id)
    {
        $res = AdminAction::destroy($this , $id);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function destroyAll()
    {
        $ids = $this->request->input('ids' , '[]');
        $ids = json_decode($ids , true);
        $res = AdminAction::destroyAll($this , $ids);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }
}
