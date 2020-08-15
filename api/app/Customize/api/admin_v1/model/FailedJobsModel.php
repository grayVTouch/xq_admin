<?php


namespace App\Customize\api\admin_v1\model;


class FailedJobsModel extends Model
{
    protected $table = 'failed_jobs';

    public static function countByDate(string $date): int
    {
        return self::whereRaw('date_format(failed_at , "%Y-%m-%d") = ?' , $date)
            ->count();
    }
}
