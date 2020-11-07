<?php


namespace App\Customize\api\admin\job;


use function core\format_path;

class FileBaseJob
{
    protected function generateRealPath(string $dir , string $path): string
    {
        return format_path(rtrim($dir , '/') . '/' . ltrim($path , '/'));
    }
}
