<?php

namespace App\Customize\api\admin\model;


class ResourceModel extends Model
{
    //
    protected $table = 'xq_resource';

    public static function updateByPath(string $path , array $data = [])
    {
        return self::where('path' , $path)
            ->update($data);
    }
}
