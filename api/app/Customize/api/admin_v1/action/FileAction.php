<?php


namespace App\Customize\api\admin_v1\action;


use App\Http\Controllers\api\admin_v1\Base;
use Core\Lib\ImageProcessor;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use function api\admin_v1\my_config;
use function api\admin_v1\get_form_error;
use function core\format_path;

class FileAction extends Action
{
    public static function upload(Base $context , ?UploadedFile $file , array $param = [])
    {
        $mode_range = my_config('business.mode_for_file');
        $validator = Validator::make($param , [
            'm' => ['sometimes' , Rule::in($mode_range)] ,
            'w' => 'sometimes|integer' ,
            'h' => 'sometimes|integer' ,
        ]);
        if ($validator->fails()) {
            return self::error('参数错误，请检查' , get_form_error($validator));
        }
        if (empty($file)) {
            return self::error('请提供上传文件');
        }
        $dir    = date('Ymd');
        $path   = Storage::put($dir , $file);
        $realpath = Storage::path($path);
        $dir_prefix = Storage::disk()->getAdapter()->getPathPrefix();
        $dir_prefix = format_path($dir_prefix) . '/';
        if (empty($param['m'])) {
            if (!empty($param['w'])) {
                $mode = 'fix-width';
                if (!empty($param['h'])) {
                    $mode = 'fix';
                }
            } else if (!empty($param['h'])) {
                $mode = 'fix-height';
            } else {
                $mode = '';
            }
        } else {
            $mode = $param['m'];
        }
        if (in_array($mode , $mode_range)) {
            $image_processor = new ImageProcessor($dir_prefix . $dir);
            $res = $image_processor->compress($realpath , [
                'mode' => $mode ,
                'ratio' => $param['r'] ,
                'width' => $param['w'] ,
                'height' => $param['h'] ,
            ] , false);
            // 删除源文件
            Storage::delete($path);
            $path = str_replace($dir_prefix , '' , $res);
        }
        return self::success('' , $path);
    }
}
