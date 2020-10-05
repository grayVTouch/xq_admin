<?php


namespace App\Customize\api\admin\action;


use App\Customize\api\admin\job\QueueHandleJob;
use App\Http\Controllers\api\admin\Base;

class JobAction extends Action
{
    public static function retry(Base $context , array $param = []): array
    {
        QueueHandleJob::dispatch('retry');
        return self::success('操作成功');
    }

    // 清空失败的队列任务
    public static function flush(Base $context , array $param = []): array
    {
        QueueHandleJob::dispatch('flush');
        return self::success('操作成功');
    }
}
