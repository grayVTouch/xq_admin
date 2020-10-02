<?php


namespace App\Customize\api\admin\model;


class FailedJobsModel extends Model
{
    protected $table = 'failed_jobs';

    public static function countByDate(string $date): int
    {
        return self::whereRaw('date_format(failed_at , "%Y-%m-%d") = ?' , $date)
            ->count();
    }
}
