<?php


namespace App\Http\Controllers\api\admin_v1;


use App\Customize\api\admin_v1\action\FileAction;
use function api\admin_v1\error;
use function api\admin_v1\success;

class File extends Base
{
    public function upload()
    {
        $file = $this->request->file('file');
        $res = FileAction::upload($this , $file);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }
}
