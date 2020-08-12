<?php

namespace App\Model;


use Illuminate\Database\Eloquent\Collection;

class TimerTaskLogModel extends Model
{
    //
    protected $table = 'xq_timer_task_log';

    public static function log(string $type , string $log , string $create_time): int
    {
        return self::insertGetId(compact('type' , 'log' , 'create_time'));
    }
}
