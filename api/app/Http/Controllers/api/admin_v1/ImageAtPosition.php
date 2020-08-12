<?php


namespace App\Http\Controllers\api\admin_v1;


use App\Customize\api\admin_v1\action\ImageAtPositionAction;
use function api\admin_v1\error;
use function api\admin_v1\success;

class ImageAtPosition extends Base
{
    public function index()
    {
        $param = $this->request->query();
        $param['name'] = $param['name'] ?? '';
        $param['platform'] = $param['platform'] ?? '';
        $param['module_id'] = $param['module_id'] ?? '';
        $param['position_id'] = $param['position_id'] ?? '';
        $param['order'] = $param['order'] ?? '';
        $param['limit'] = $param['limit'] ?? '';
        $res = ImageAtPositionAction::index($this , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function update($id)
    {
        $param = $this->request->post();
        $param['module_id']   = $param['module_id'] ?? '';
        $param['position_id']   = $param['position_id'] ?? '';
        $param['path']          = $param['path'] ?? '';
        $param['link']          = $param['link'] ?? '';
        $res = ImageAtPositionAction::update($this , $id ,$param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function store()
    {
        $param = $this->request->post();
        $param['module_id']   = $param['module_id'] ?? '';
        $param['position_id']   = $param['position_id'] ?? '';
        $param['path']          = $param['path'] ?? '';
        $param['link']          = $param['link'] ?? '';
        $res = ImageAtPositionAction::store($this ,$param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function show($id)
    {
        $res = ImageAtPositionAction::show($this , $id);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function destroy($id)
    {
        $res = ImageAtPositionAction::destroy($this , $id);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function destroyAll()
    {
        $ids = $this->request->input('ids' , '[]');
        $ids = json_decode($ids , true);
        $res = ImageAtPositionAction::destroyAll($this , $ids);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function search()
    {
        $value = $this->request->get('value' , '');
        $res = ImageAtPositionAction::search($this , $value);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function all()
    {
        $res = ImageAtPositionAction::all($this);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }
}
