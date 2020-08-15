<?php


namespace App\Customize\api\admin_v1\util;


use App\Customize\api\admin_v1\handler\DiskHandler;
use App\Customize\api\admin_v1\model\DiskModel;
use Core\Lib\File;
use Exception;
use Illuminate\Http\UploadedFile;
use stdClass;
use function core\format_path;
use function core\get_extension;
use function core\random;

class FileUtil
{
    private static $disk = null;

    public static function disk(): ?stdClass
    {
        if (!empty($disk)) {
            return self::$disk;
        }
        $disk = DiskModel::findDefault();
        if (empty($disk)) {
            throw new Exception('请添加默认的可用存储路径');
        }
        $disk = DiskHandler::handle($disk);
        return self::$disk = $disk;
    }

    // 生成随机的文件名
    public static function filename(): string
    {
        return date('YmdHis') . random(6 , 'letter' , true);
    }

    // 上传文件保存
    public static function upload(?UploadedFile $uploaded_file , string $dir = ''): ?string
    {
        $dir        = empty($dir) ? '' : trim($dir , '/');
        $save_dir   = format_path(self::dir() . '/' . $dir);
        $source     = $uploaded_file->getRealPath();
        $extension  = $uploaded_file->getClientOriginalExtension();
        $filename   = self::filename();
        $file       = $filename . '.' . $extension;
        $target     = $save_dir . '/' . $file;
        File::mkdir($save_dir  , 0755 , true);
        if (!File::moveUploadedFile($source , $target)) {
            return false;
        }
        $prefix = self::prefix();
        return format_path($prefix . '/' . $dir . '/' . $file);
    }

    // 普通文件移动
    public static function move(string $path , string $dir = ''): ?string
    {
        $dir        = empty($dir) ? '' : trim($dir , '/');
        $save_dir   = format_path(self::dir() . '/' . $dir);
        $extension  = get_extension($path);
        $filename   = self::filename();
        $file       = $filename . '.' . $extension;
        $target     = $save_dir . '/' . $file;
        File::mkdir($save_dir  , 0755 , true);
        if (!File::move($path , $target)) {
            return false;
        }
        $prefix = self::prefix();
        return format_path($prefix . '/' . $dir . '/' . $file);
    }

    // 普通文件保存
    public static function save(string $content , string $file , string $dir = ''): string
    {
        $dir        = empty($dir) ? '' : trim($dir , '/');
        $save_dir   = format_path(self::dir() . '/' . $dir);
        $target     = $save_dir . '/' . $file;
        $prefix     = self::prefix();
        File::mkdir($save_dir , 0755 , true);
        File::write($target , $content);
        return format_path($prefix . '/' . $dir . '/' . $file);
    }

    // 根据负荷规则的相对路径获取绝对路径
    public static function getRealPathByRelativePath(string $relative_path = ''): string
    {
        if (empty($relative_path)) {
            return '';
        }
        $parse = explode('/' , $relative_path);
        if (empty($parse)) {
            throw new Exception('提供的路径不符合规范');
        }
        $prefix = $parse[0];
        $disk = DiskModel::findByPrefix($prefix);
        if (empty($disk)) {
            throw new Exception('磁盘记录未找到');
        }
        return format_path($disk->path . '/' . ltrim(str_replace($prefix , '' , $relative_path) , '/\\'));
    }


    // 根据用户提供的路径生成符合规则的相对路径
    public static function getRelativePath(string $path = ''): string
    {
        if (empty($path)) {
            return '';
        }
        $prefix = self::prefix();
        return $prefix . '/' . ltrim($path , '/');
    }

    // 通过给定的路径生成实际保存的绝对路径
    public static function getRealPath(string $path = ''): string
    {
        return self::getRealPathByRelativePath(self::getRelativePath($path));
    }

    // 删除文件（通过相对路径）
    public static function delete(string $relative_path = ''): void
    {
        $real_path = self::getRealPath($relative_path);
        if (!File::exists($real_path)) {
            return ;
        }
        File::delete($real_path);
    }

    // 文件保存目录
    public static function dir(string $suffix = ''): string
    {
        $disk = $disk = self::disk();
        $suffix = empty($suffix) ? '' : '/' . ltrim($suffix , '/');
        return format_path($disk->path . $suffix);
    }

    /**
     * 文件路径前缀
     *
     * @return  string
     * @throws Exception
     */
    public static function prefix(): string
    {
        $disk = self::disk();
        return $disk->prefix;
    }

    /**
     * 生成 url
     *
     * @param  string $relative_path
     * @return string
     */
    public static function url(string $relative_path = '')
    {
        $res_url        = rtrim(config('my.res_url' , '') , '/');
        $relative_path  = ltrim($relative_path , '/');
        return $res_url . '/' . $relative_path;
    }

    // 检查文件是否存在
    public static function exists(string $relative_path = ''): bool
    {
        $real_path = self::getRealPathByRelativePath($relative_path);
        return File::exists($real_path);
    }
}
