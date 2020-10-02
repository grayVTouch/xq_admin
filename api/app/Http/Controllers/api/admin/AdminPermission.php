<?php


namespace App\Http\Controllers\api\admin;


use App\Customize\api\admin\action\AdminPermissionAction;
use App\Customize\api\admin\action\UserAction;
use function api\admin\error;
use function api\admin\success;

class AdminPermission extends Base
{
    public function index()
    {
        $res = AdminPermissionAction::index($this);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function allExcludeSelfAndChildren($id)
    {
        $res = AdminPermissionAction::allExcludeSelfAndChildren($this , $id);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function localUpdate($id)
    {
        $param = $this->request->post();
        $param['value']     = $param['value'] ?? '';
        $param['p_id']        = $param['p_id'] ?? '';
        $param['cn']        = $param['cn'] ?? '';
        $param['en']        = $param['en'] ?? '';
        $param['description'] = $param['description'] ?? '';
        $param['type']      = $param['type'] ?? '';
        $param['is_menu']   = $param['is_menu'] ?? '';
        $param['is_view']   = $param['is_view'] ?? '';
        $param['enable']    = $param['enable'] ?? '';
        $param['weight']    = $param['weight'] ?? '';
        $param['s_ico']     = $param['s_ico'] ?? '';
        $param['b_ico']     = $param['b_ico'] ?? '';
        $res = AdminPermissionAction::localUpdate($this , $id ,$param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function update($id)
    {
        $param = $this->request->post();
        $param['value']     = $param['value'] ?? '';
        $param['p_id']        = $param['p_id'] ?? '';
        $param['cn']        = $param['cn'] ?? '';
        $param['en']        = $param['en'] ?? '';
        $param['description'] = $param['description'] ?? '';
        $param['type']      = $param['type'] ?? '';
        $param['is_menu']   = $param['is_menu'] ?? '';
        $param['is_view']   = $param['is_view'] ?? '';
        $param['enable']    = $param['enable'] ?? '';
        $param['weight']    = $param['weight'] ?? '';
        $param['s_ico']     = $param['s_ico'] ?? '';
        $param['b_ico']     = $param['b_ico'] ?? '';
        $res = AdminPermissionAction::update($this , $id ,$param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function store()
    {
        $param = $this->request->post();
        $param['value']     = $param['value'] ?? '';
        $param['p_id']        = $param['p_id'] ?? '';
        $param['cn']        = $param['cn'] ?? '';
        $param['en']        = $param['en'] ?? '';
        $param['description'] = $param['description'] ?? '';
        $param['type']      = $param['type'] ?? '';
        $param['is_menu']   = $param['is_menu'] ?? '';
        $param['is_view']   = $param['is_view'] ?? '';
        $param['enable']    = $param['enable'] ?? '';
        $param['weight']    = $param['weight'] ?? '';
        $param['s_ico']     = $param['s_ico'] ?? '';
        $param['b_ico']     = $param['b_ico'] ?? '';
        $res = AdminPermissionAction::store($this ,$param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function show($id)
    {
        $res = AdminPermissionAction::show($this , $id);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function destroy($id)
    {
        $res = AdminPermissionAction::destroy($this , $id);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function destroyAll()
    {
        $ids = $this->request->input('ids' , '[]');
        $ids = json_decode($ids , true);
        $res = AdminPermissionAction::destroyAll($this , $ids);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

}
