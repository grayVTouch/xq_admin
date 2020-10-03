<?php

namespace App\Customize\api\admin\model;


class ResourceModel extends Model
{
    //
    protected $table = 'xq_resource';

    public static function updateByUrl(string $url , array $data = [])
    {
        return self::where('url' , $url)
            ->update($data);
    }

    public static function findByUrl(string $url): ?ResourceModel
    {
        return self::where('url' , $url)->first();
    }
}
