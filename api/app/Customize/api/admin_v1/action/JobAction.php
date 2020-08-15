<?php


namespace App\Customize\api\admin_v1\action;


use App\Customize\api\admin_v1\job\QueueHandleJob;
use App\Http\Controllers\api\admin_v1\Base;

class JobAction extends Action
{
    public static function retry(Base $context , array $param = []): array
    {
        QueueHandleJob::dispatch('retry');
        return self::success();
    }

    // 清空失败的队列任务
    public static function flush(Base $context , array $param = []): array
    {
        QueueHandleJob::dispatch('flush');
        return self::success();
    }
}
