<?php


namespace App\Http\Controllers\api\v1;


use App\Customize\api\v1\action\AdminPermissionAction;
use App\Customize\api\v1\action\UserAction;
use function api\v1\error;
use function api\v1\success;

class AdminPermission extends Base
{
    public function index()
    {
        $res = AdminPermissionAction::index($this);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
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
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
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
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
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
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function show($id)
    {
        $res = AdminPermissionAction::show($this , $id);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function destroy($id)
    {
        $res = AdminPermissionAction::destroy($this , $id);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function destroyAll()
    {
        $id_list = $this->request->input('id_list' , '[]');
        $id_list = json_decode($id_list , true);
        $res = AdminPermissionAction::destroyAll($this , $id_list);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

}
