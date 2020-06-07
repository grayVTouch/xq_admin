<?php


namespace App\Customize\api\v1\model;

use Illuminate\Database\Eloquent\Model as BaseModel;
use function core\convert_obj;

class Model extends BaseModel
{
    public $timestamps = false;

    public static function updateById(int $id , array $data = [])
    {
        return static::where('id' , $id)
            ->update($data);
    }

    public static function updateByIds(array $id_list = [] , array $data = [])
    {
        return static::whereIn('id' , $id_list)
            ->update($data);
    }

    public static function findById(int $id)
    {
        $res = static::find($id);
        if (empty($res)) {
            return ;
        }
        $res = convert_obj($res);
        return $res;
    }

    public static function delById(int $id)
    {
        return static::where('id' , $id)->delete();
    }

    public static function delByIds(array $id_list = [])
    {
        return static::whereIn('id' , $id_list)
            ->delete();
    }
}
