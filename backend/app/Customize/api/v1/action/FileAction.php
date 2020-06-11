<?php


namespace App\Customize\api\v1\action;


use App\Http\Controllers\api\v1\Base;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use function api\v1\my_config;

class FileAction extends Action
{
    public static function upload(Base $context , ?UploadedFile $file , array $param = [])
    {
        if (empty($file)) {
            return self::error('请提供上传文件');
        }
        $ext_range  = my_config('business.file');
        $ext        = $file->extension();
        $filename   = $file->getClientOriginalName();
        if (!in_array($ext , $ext_range)) {
            return self::error('不支持的文件类型，当前支持的文件类型有：' . implode(',' , $ext_range));
        }
        $dir    = date('Ymd');
        $path   = Storage::disk('public')->put($dir , $file);
        $path   = ltrim($path , '/');
        $storage = my_config('app.storage_soft_link');
        $path   = sprintf('/%s/%s' , $storage , $path);
        return self::success($path);
    }
}
