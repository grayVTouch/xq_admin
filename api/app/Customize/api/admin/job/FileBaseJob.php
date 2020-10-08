<?php


namespace App\Customize\api\admin\job;


use App\Customize\api\admin\model\DiskModel;
use function api\admin\my_config;
use function core\format_path;

class FileBaseJob
{
    /**
     * @var DiskModel
     */
    protected $disk;

    protected function generateRealPath(string $dir , string $path): string
    {
        return format_path(rtrim($dir , '/') . '/' . ltrim($path , '/'));
    }

    /**
     * 从绝对路径生成相对路径
     *
     * @param string $path
     * @return string
     * @throws \Exception
     */
    protected function generateUrlByRealPath(string $real_path = ''): string
    {
        $real_path                      = format_path($real_path);
        $res_url                        = my_config('app.res_url');
        $res_url                        = rtrim($res_url , '/');
        $relative_path_without_prefix   = ltrim(str_replace($this->disk->path , '' , $real_path) , '/');
        $relative_path_with_prefix      = $this->disk->prefix . '/' . $relative_path_without_prefix;
        return $res_url . '/' . $relative_path_with_prefix;
    }
}
