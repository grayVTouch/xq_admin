<?php


namespace App\Http\Controllers\api\admin_v1;


use App\Customize\api\admin_v1\action\FileAction;
use function api\admin_v1\error;
use function api\admin_v1\success;

class File extends Base
{
    public function upload()
    {
        $param = $this->request->input();
        $param['m'] = $param['m'] ?? '';
        $param['w'] = $param['w'] ?? '';
        $param['h'] = $param['h'] ?? '';
        $param['r'] = $param['r'] ?? '';
        $file = $this->request->file('file');
        $res = FileAction::upload($this , $file , $param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }
}
