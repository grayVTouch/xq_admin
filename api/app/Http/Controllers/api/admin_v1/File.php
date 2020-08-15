<?php


namespace App\Http\Controllers\api\admin_v1;


use App\Customize\api\admin_v1\action\FileAction;
use function api\admin_v1\error;
use function api\admin_v1\success;

class File extends Base
{
    public function upload()
    {
        $param = $this->request->post();
        $param['file'] = $this->request->file('file');
        $res = FileAction::upload($this , $param['file'] , $param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function uploadImage()
    {
        $param = $this->request->post();
        $param['m'] = $param['m'] ?? '';
        $param['w'] = $param['w'] ?? '';
        $param['h'] = $param['h'] ?? '';
        $param['r'] = $param['r'] ?? '';
        $param['file'] = $this->request->file('file');
        $res = FileAction::uploadImage($this , $param['file'] , $param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function uploadVideo()
    {
        $param = $this->request->post();
        $param['file'] = $this->request->file('file');
        $res = FileAction::uploadVideo($this , $param['file'] , $param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function uploadSubtitle()
    {
        $param = $this->request->post();
        $param['file'] = $this->request->file('file');
        $res = FileAction::uploadSubtitle($this , $param['file'] , $param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }

    public function uploadOffice()
    {
        $param = $this->request->post();
        $param['file'] = $this->request->file('file');
        $res = FileAction::uploadOffice($this , $param['file'] , $param);
        if ($res['code'] != 0) {
            return error($res['message'] , $res['data'] , $res['code']);
        }
        return success($res['message'] , $res['data']);
    }
}
