<?php

namespace App\Customize\api\admin_v1\job;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class QueueHandleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * 支持的动作有 retry | flush
     * @var string
     */
    private $action = '';

    private $artisan = '';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $action)
    {
        $this->action = $action;
        $this->artisan = base_path('artisan');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        switch ($this->action)
        {
            case 'retry':
                $this->retry();
                break;
            case 'flush':
                $this->flush();
                break;
            default:
                throw new Exception('不支持的动作:' . $this->action);
        }

    }

    // 重试失败任务
    private function retry()
    {
        $command = sprintf('php %s queue:retry all' , $this->artisan);
        exec($command , $res , $status);
        if ($status > 0) {
            $msg = '重新执行失败队列发生错误！错误信息：' . implode("\n" , $res);
            throw new Exception($msg);
        }
    }

    // 清空失败队列
    private function flush()
    {
        $command = sprintf('php %s queue:flush' , $this->artisan);
        exec($command , $res , $status);
        if ($status > 0) {
            $msg = '清空失败队列发生错误！错误信息：' . implode("\n" , $res);
            throw new Exception($msg);
        }
    }
}
