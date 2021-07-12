<?php


namespace App\Http\Controllers\api\web;


use App\Customize\api\web\action\FileAction;
use function api\web\error;
use function api\web\success;

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
        $param = $this->request->query();
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
