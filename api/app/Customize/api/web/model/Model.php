<?php


namespace App\Customize\api\web\model;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    public $timestamps = false;

    public static function updateById(int $id , array $data = [])
    {
        return static::where('id' , $id)
            ->update($data);
    }

    public static function updateByIds(array $ids = [] , array $data = [])
    {
        return static::whereIn('id' , $ids)
            ->update($data);
    }

    public static function getAll()
    {
        $res = static::orderBy('id' , 'asc')
            ->get();
        return $res;
    }

    public static function delById(int $id)
    {
        return static::where('id' , $id)->delete();
    }

    public static function delByIds(array $ids = [])
    {
        return static::whereIn('id' , $ids)
            ->delete();
    }
}