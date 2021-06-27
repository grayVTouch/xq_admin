<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    public $timestamps = false;

    public static function updateById(int $id , $data): int
    {
        return static::where('id' , $id)->update($data);
    }
}
