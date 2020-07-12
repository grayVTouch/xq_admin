<?php


namespace App\Customize\api\web_v1\action;


use App\Http\Controllers\api\web_v1\Base;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileAction extends Action
{
    public static function upload(Base $context , ?UploadedFile $file , array $param = [])
    {
        if (empty($file)) {
            return self::error('请提供上传文件');
        }
        $dir    = date('Ymd');
        $path   = Storage::put($dir , $file);
        return self::success($path);
    }
}
