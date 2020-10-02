<?php

namespace App\Model;


use Illuminate\Database\Eloquent\Collection;

class TimerTaskLogModel extends Model
{
    //
    protected $table = 'xq_timer_task_log';

    public static function log(string $type , string $log , string $created_at): int
    {
        return self::insertGetId(compact('type' , 'log' , 'created_at'));
    }
}
