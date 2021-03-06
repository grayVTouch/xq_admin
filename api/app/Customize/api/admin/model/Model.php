<?php


namespace App\Customize\api\admin\model;

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

    public static function countByDate(string $date): int
    {
        return self::whereRaw('date_format(created_at , "%Y-%m-%d") = ?' , $date)
            ->count();
    }

    public static function countByMonth(string $month): int
    {
        return self::whereRaw('date_format(created_at , "%Y-%m") = ?' , $month)
            ->count();
    }

    public static function countByYear(string $year): int
    {
        return self::whereRaw('date_format(created_at , "%Y") = ?' , $year)
            ->count();
    }

    public static function getByIds(array $ids): Collection
    {
        return self::whereIn('id' , $ids)->get();
    }
}
