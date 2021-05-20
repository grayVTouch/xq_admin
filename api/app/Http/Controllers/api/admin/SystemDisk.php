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
        $param['parent_path'] = $param['parent_path'] ?? "";
        $res = SystemDiskAction::index($this , $param);
        if ($res['code'] !== 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }
}
