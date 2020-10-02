<?php


namespace App\Http\Controllers\api\admin;


use App\Customize\api\admin\action\ModuleAction;
use function api\admin\error;
use function api\admin\success;

class Module extends Base
{
    public function index()
    {
        $param = $this->request->query();
        $param['name'] = $param['name'] ?? '';
        $param['order'] = $param['order'] ?? '';
        $param['limit'] = $param['limit'] ?? '';
        $res = ModuleAction::index($this , $param);
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
        $param['enable']        = $param['enable'] ?? '';
        $param['auth']          = $param['auth'] ?? '';
        $param['auth_password'] = $param['auth_password'] ?? '';
        $param['default']        = $param['default'] ?? '';
        $param['weight']        = $param['weight'] ?? '';

        $res = ModuleAction::update($this , $id ,$param);

        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }

        return success($res['message'] , $res['data']);
    }

    public function localUpdate($id)
    {
        $param = $this->request->post();
        $param['name']          = $param['name'] ?? '';
        $param['description']   = $param['description'] ?? '';
        $param['enable']        = $param['enable'] ?? '';
        $param['weight']        = $param['weight'] ?? '';
        $param['auth']        = $param['auth'] ?? '';
        $param['auth_password']        = $param['auth_password'] ?? '';
        $param['default']        = $param['default'] ?? '';
        $res = ModuleAction::localUpdate($this , $id ,$param);
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
        $param['enable']        = $param['enable'] ?? '';
        $param['weight']        = $param['weight'] ?? '';
        $param['auth']        = $param['auth'] ?? '';
        $param['auth_password']        = $param['auth_password'] ?? '';
        $param['default']        = $param['default'] ?? '';
        $res = ModuleAction::store($this ,$param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function show($id)
    {
        $res = ModuleAction::show($this , $id);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function destroy($id)
    {
        $res = ModuleAction::destroy($this , $id);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function destroyAll()
    {
        $ids = $this->request->input('ids' , '[]');
        $ids = json_decode($ids , true);
        $res = ModuleAction::destroyAll($this , $ids);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function all()
    {
        $res = ModuleAction::all($this);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }
}
