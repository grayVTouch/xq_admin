<?php


namespace App\Customize\api\admin\job\middleware;

class BootMiddleware
{

    /**
     * 处理队列任务
     *
     * @param  mixed  $job
     * @param  callable  $next
     * @return mixed
     */
    public function handle($job, $next)
    {
        $this->resoveDependencies();
        $next($job);
    }

    // 解决依赖
    public function resoveDependencies()
    {
        // 注意部分方法不可用于 命令行下
        require_once __DIR__ . '/../../common/common.php';

        require_once __DIR__ . '/../../plugin/extra/app.php';
    }
}
