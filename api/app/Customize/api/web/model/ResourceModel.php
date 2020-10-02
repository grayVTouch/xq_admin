<?php

namespace App\Customize\api\web\model;


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
