<?php


namespace App\Model;


class DiskModel extends Model
{
    protected $table = 'xq_disk';

    public static function findByPrefix(string $prefix = ''): ?DiskModel
    {
        return self::where('prefix' , $prefix)->first();
    }
}
