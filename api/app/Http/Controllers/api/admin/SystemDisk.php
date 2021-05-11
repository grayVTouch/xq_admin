<?php


namespace App\Http\Controllers\api\admin;


use App\Customize\api\admin\action\SystemDiskAction;
use function api\admin\error;
use function api\admin\success;

class SystemDisk extends Base
{
    public function index()
    {
        $param = $this->request->query();
        $param['name'] = $param['name'] ?? '';
        $param['order'] = $param['order'] ?? '';
        $param['limit'] = $param['limit'] ?? '';
        $res = SystemDiskAction::index($this , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function localUpdate($id)
    {
        $param = $this->request->post();
        $param['path']          = $param['path'] ?? '';
        $param['os']            = $param['os'] ?? '';
        $param['prefix']        = $param['prefix'] ?? '';
        $param['is_default']       = $param['is_default'] ?? '';
        $param['is_linked']       = $param['is_linked'] ?? '';
        $res = SystemDiskAction::localUpdate($this , $id ,$param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function update($id)
    {
        $param = $this->request->post();
        $param['path']          = $param['path'] ?? '';
        $param['os']            = $param['os'] ?? '';
        $param['prefix']        = $param['prefix'] ?? '';
        $param['is_default']       = $param['is_default'] ?? '';
        $param['is_linked']       = $param['is_linked'] ?? '';
        $res = SystemDiskAction::update($this , $id ,$param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function store()
    {
        $param  = $this->request->post();
        $param['path']          = $param['path'] ?? '';
        $param['os']            = $param['os'] ?? '';
        $param['prefix']        = $param['prefix'] ?? '';
        $param['is_default']       = $param['is_default'] ?? '';
        $param['is_linked']       = $param['is_linked'] ?? '';
        $res = SystemDiskAction::store($this ,$param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function show($id)
    {
        $res = SystemDiskAction::show($this , $id);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function destroy($id)
    {
        $res = SystemDiskAction::destroy($this , $id);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function destroyAll()
    {
        $ids = $this->request->input('ids' , '[]');
        $ids = json_decode($ids , true);
        $res = SystemDiskAction::destroyAll($this , $ids);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function link()
    {
        $ids = $this->request->input('ids' , '[]');
        $ids = json_decode($ids , true);
        $res = SystemDiskAction::link($this , $ids);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }
}
