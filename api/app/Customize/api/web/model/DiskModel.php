<?php


namespace App\Customize\api\web\model;


class DiskModel extends Model
{
    protected $table = 'xq_disk';

    public static function findDefault(): ?DiskModel
    {
        return self::where('default' , 1)->first();
    }

    public static function findByPrefix(string $prefix = ''): ?DiskModel
    {
        return self::where('prefix' , $prefix)->first();
    }
}
