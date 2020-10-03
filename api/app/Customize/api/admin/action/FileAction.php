<?php


namespace App\Customize\api\admin\action;


use App\Customize\api\admin\util\FileUtil;
use App\Customize\api\admin\util\ResourceUtil;
use App\Http\Controllers\api\admin\Base;
use Core\Lib\ImageProcessor;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use function api\admin\my_config;
use function api\admin\get_form_error;

class FileAction extends Action
{
    // 上传文件（任意类型文件）
    public static function upload(Base $context , ?UploadedFile $file , array $param = [])
    {
        $validator = Validator::make($param , [
            'file' => 'required' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }
        $dir        = date('Ymd');
        $path       = FileUtil::upload($file , $dir);
        $real_path  = FileUtil::getRealPathByRelativePathWithPrefix($path);
        $url        = FileUtil::url($path);
        ResourceUtil::create($url , $real_path , 'local');
        return self::success('' , $url);
    }

    // 上传图片
    public static function uploadImage(Base $context , ?UploadedFile $file , array $param = [])
    {
        $mode_range = my_config('business.mode_for_file');
        $validator = Validator::make($param , [
            'm' => ['sometimes' , Rule::in($mode_range)] ,
            'w' => 'sometimes|integer' ,
            'h' => 'sometimes|integer' ,
            'file' => 'required|mimes:jpg,jpeg,png,gif' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }
        $dir        = date('Ymd');
        $path       = FileUtil::upload($file , $dir);
        $real_path  = FileUtil::getRealPathByRelativePathWithPrefix($path);
        $url        = FileUtil::url($path);
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
            $real_path          = FileUtil::getRealPathByRelativePathWithPrefix($path);
            $image_handle_dir   = FileUtil::dir($dir);
            $image_processor    = new ImageProcessor($image_handle_dir);
            $real_path = $image_processor->compress($real_path , [
                'mode'      => $mode ,
                'ratio'     => $param['r'] ,
                'width'     => $param['w'] ,
                'height'    => $param['h'] ,
            ] , false);
            // 删除源文件
            FileUtil::delete($path);
            $relative_path  = FileUtil::prefix() . '/' . str_replace(FileUtil::dir() . '/' , '' , $real_path);
            $url            = FileUtil::url($relative_path);
        }
        ResourceUtil::create($url , $real_path , 'local');
        return self::success('' , $url);
    }

    // 上传视频
    public static function uploadVideo(Base $context , ?UploadedFile $file , array $param = [])
    {
        $validator = Validator::make($param , [
            'file' => 'required|mimes:mp4,mov,mkv,avi,flv,rm,rmvb,ts' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }
        $dir        = date('Ymd');
        $path       = FileUtil::upload($file , $dir);
        $real_path  = FileUtil::getRealPathByRelativePathWithPrefix($path);
        $url        = FileUtil::url($path);
        ResourceUtil::create($url , $real_path , 'local');
        return self::success('' , $url);
    }

    // 上传视频字幕
    public static function uploadSubtitle(Base $context , ?UploadedFile $file , array $param = [])
    {
        $validator = Validator::make($param , [
            'file' => 'required|mimes:ass,idx,sub,srt,vtt,ssa' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }
//        $extension = $file->getClientOriginalExtension();
//        $ext_range = ['ass' , 'idx' , 'sub' , 'srt' , 'vtt' , 'ssa'];
//        if (!in_array($extension , $ext_range)) {
//            return self::error('不支持的文件类型');
//        }
        $dir        = date('Ymd');
        $path       = FileUtil::upload($file , $dir);
        $real_path  = FileUtil::getRealPathByRelativePathWithPrefix($path);
        $url        = FileUtil::url($path);
        ResourceUtil::create($url , $real_path , 'local');
        return self::success('' , $url);
    }

    // 上传文档|电子表格之类
    public static function uploadOffice(Base $context , ?UploadedFile $file , array $param = [])
    {
        $validator = Validator::make($param , [
            'file' => 'required|mimes:doc,docx,xls,xlsx' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }
        $dir        = date('Ymd');
        $path       = FileUtil::upload($file , $dir);
        $real_path  = FileUtil::getRealPathByRelativePathWithPrefix($path);
        $url        = FileUtil::url($path);
        ResourceUtil::create($url , $real_path , 'local');
        return self::success('' , $url);
    }

}
