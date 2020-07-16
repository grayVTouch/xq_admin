<?php


namespace App\Http\Controllers\api\admin_v1;


use App\Customize\api\admin_v1\action\PositionAction;
use function api\admin_v1\error;
use function api\admin_v1\success;

class Position extends Base
{
    public function index()
    {
        $param = $this->request->query();
        $param['value'] = $param['value'] ?? '';
        $param['order'] = $param['order'] ?? '';
        $param['limit'] = $param['limit'] ?? '';
        $res = PositionAction::index($this , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function update($id)
    {
        $param = $this->request->post();
        $param['value']          = $param['value'] ?? '';
        $param['name']        = $param['name'] ?? '';
        $param['description']        = $param['description'] ?? '';
        $param['platform']        = $param['platform'] ?? '';
        $res = PositionAction::update($this , $id ,$param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function store()
    {
        $param = $this->request->post();
        $param['value']        = $param['value'] ?? '';
        $param['name']        = $param['name'] ?? '';
        $param['description']        = $param['description'] ?? '';
        $param['platform']        = $param['platform'] ?? '';
        $res = PositionAction::store($this ,$param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function show($id)
    {
        $res = PositionAction::show($this , $id);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function destroy($id)
    {
        $res = PositionAction::destroy($this , $id);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function destroyAll()
    {
        $ids = $this->request->input('ids' , '[]');
        $ids = json_decode($ids , true);
        $res = PositionAction::destroyAll($this , $ids);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function search()
    {
        $value = $this->request->get('value' , '');
        $res = PositionAction::search($this , $value);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function all()
    {
        $res = PositionAction::all($this);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }
}
