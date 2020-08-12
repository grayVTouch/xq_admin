<?php

namespace App\Customize\api\admin_v1\job;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RestartFaildJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $artisan = base_path('artisan');
        $command = sprintf('php %s queue:retry all' , $artisan);
        exec($command , $res , $status);
        if ($status > 0) {
            $msg = '重新执行失败队列发生错误！错误信息：' . implode("\n" , $res);
            throw new Exception($msg);
        }
    }
}
