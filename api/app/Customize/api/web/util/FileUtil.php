<?php


namespace App\Customize\api\web\util;


use App\Customize\api\web\handler\DiskHandler;
use App\Customize\api\web\model\DiskModel;
use Core\Lib\File;
use Exception;
use Illuminate\Http\UploadedFile;
use stdClass;
use function api\web\my_config;
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

    /**
     * 保存上传文件
     *
     * @param UploadedFile|null $uploaded_file
     * @param string $dir
     * @return string|null 相对路径
     * @throws Exception
     */
    public static function upload(?UploadedFile $uploaded_file , string $dir = ''): ?string
    {
        $save_dir   = self::dir('system' , $dir);
        $source     = $uploaded_file->getRealPath();
        $extension  = $uploaded_file->getClientOriginalExtension();
        $filename   = self::filename();
        $file       = $filename . '.' . $extension;
        $target     = $save_dir . '/' . $file;
        File::mkdir($save_dir  , 0755 , true);
        if (!File::moveUploadedFile($source , $target)) {
            return false;
        }
        return self::generateRelativePathWithPrefix($target);
    }

    // 普通文件移动
    public static function move(string $path , string $dir = ''): ?string
    {
        $save_dir   = self::dir('system' , $dir);
        $extension  = get_extension($path);
        $filename   = self::filename();
        $file       = $filename . '.' . $extension;
        $target     = $save_dir . '/' . $file;
        File::mkdir($save_dir  , 0755 , true);
        if (!File::move($path , $target)) {
            return false;
        }
        return self::generateRelativePathWithPrefix($target);
    }

    // 普通文件保存
    public static function save(string $content , string $file , string $dir = ''): string
    {
        $save_dir   = self::dir('system' , $dir);
        $target     = $save_dir . '/' . $file;
        File::mkdir($save_dir , 0755 , true);
        File::write($target , $content);
        $path = self::path();
        $relative_path = str_replace($path , '' , $target);
        $relative_path = ltrim($relative_path , '/');
        $prefix     = self::prefix();
        return format_path($prefix . '/' . $relative_path);
    }

    /**
     * 通过 带有路径前缀的相对路径 生成 实际可访问的绝对路径
     *
     * @param string $relative_path 带有路径前缀的相对路径
     * @return string
     * @throws Exception
     */
    public static function generateRealPath(string $relative_path = ''): string
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
        $path = $disk->path . '/' . ltrim(str_replace($prefix , '' , $relative_path) , '/\\');
        return format_path($path);
    }


    // 根据用户提供的路径生成符合规则的相对路径
    public static function generateRelativePathWithPrefix(string $path = ''): string
    {
        if (empty($path)) {
            return '';
        }
        $prefix = self::prefix();
        $dir    = self::path();
        $relative_path = str_replace($dir , '' , $path);
        $relative_path = ltrim($relative_path , '/');
        return format_path($prefix . '/' . $relative_path);
    }

    /**
     * 通过给定的路径生成实际保存的绝对路径
     *
     * @param string $relative_path
     * @return string
     * @throws Exception
     */
    public static function generateRealPathByRelativePathWithoutPrefix(string $path = ''): string
    {
        $path = self::generateRelativePathWithPrefix($path);
        return self::generateRealPath($path);
    }

    // 删除文件（通过相对路径）
    public static function deleteWithoutPrefix(string $relative_path = ''): void
    {
        $real_path = self::generateRealPathByRelativePathWithoutPrefix($relative_path);
        if (!File::exists($real_path)) {
            return ;
        }
        File::delete($real_path);
    }

    // 删除文件（通过相对路径）
    public static function deleteWithPrefix(string $relative_path = ''): void
    {
        $real_path = self::generateRealPath($relative_path);
        if (!File::exists($real_path)) {
            return ;
        }
        File::delete($real_path);
    }

    /**
     * 从目录前缀生成文件保存目录的绝对路径
     *
     * @param string $suffix
     * @return string
     * @throws Exception
     */
    public static function dir(string $type , string $suffix = ''): string
    {
        $dir_prefix = my_config('app.dir')[$type];
        $disk = $disk = self::disk();
        $suffix = empty($suffix) ? '' : trim($suffix , '/');
        $dir = $disk->path . '/' . $dir_prefix . '/' . $suffix;
        return format_path($dir);
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

    public static function path(): string
    {
        $disk = self::disk();
        return $disk->path;
    }

    /**
     * 从相对路径生成访问网络地址
     *
     * @param  string $path
     * @return string
     */
    public static function generateUrlByRelativePath(string $relative_path): string
    {
        $res_url        = my_config('app.res_url');
        $res_url        = rtrim($res_url , '/');
        $relative_path  = ltrim($relative_path , '/');
        return $res_url . '/' . $relative_path;
    }

    /**
     * 从绝对路径生成相对路径
     *
     * @param  string $path
     * @return string
     */
    public static function generateUrlByRealPath(string $real_path = ''): string
    {
        $real_path                      = format_path($real_path);
        $relative_path_with_prefix      = self::generateRelativePathWithPrefix($real_path);
        $res_url                        = my_config('app.res_url');
        $res_url                        = rtrim($res_url , '/');
        return $res_url . '/' . $relative_path_with_prefix;
    }

    /**
     * 从相对路径判断其实际路径对应的文件是否存在
     *
     * @param string $relative_path
     * @return bool
     * @throws Exception
     */
    public static function existsByRelativePathWithPrefix(string $relative_path = ''): bool
    {
        $real_path = self::generateRealPath($relative_path);
        return File::exists($real_path);
    }

    /**
     * 检查给定的目录是否存在
     *
     * @param string $relative_path
     * @return bool
     * @throws Exception
     */
    public static function existsByRelativePathWithoutPrefix(string $relative_path = ''): bool
    {
        $real_path = self::generateRealPathByRelativePathWithoutPrefix($relative_path);
        return file_exists($real_path);
    }
}
