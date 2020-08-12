<?php


namespace App\Customize\api\admin_v1\action;


use App\Customize\api\admin_v1\util\FileUtil;
use App\Customize\api\admin_v1\util\ResourceUtil;
use App\Http\Controllers\api\admin_v1\Base;
use Core\Lib\ImageProcessor;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use function api\admin_v1\my_config;
use function api\admin_v1\get_form_error;

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
        $path   = FileUtil::upload($file , $dir);
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
            $realpath = FileUtil::getRealPathByRelativePath($path);
            $image_processor = new ImageProcessor(FileUtil::dir($dir));
            $res = $image_processor->compress($realpath , [
                'mode' => $mode ,
                'ratio' => $param['r'] ,
                'width' => $param['w'] ,
                'height' => $param['h'] ,
            ] , false);
            // 删除源文件
            FileUtil::delete($path);
            $path = FileUtil::prefix() . '/' . str_replace(FileUtil::dir() . '/' , '' , $res);
        }
        ResourceUtil::create($path);
        return self::success('' , $path);
    }
}
