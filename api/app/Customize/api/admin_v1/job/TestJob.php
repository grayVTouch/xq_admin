<?php

namespace App\Customize\api\admin_v1\job;

use App\Customize\api\admin_v1\job\middleware\BootMiddleware;
use App\Customize\api\admin_v1\model\VideoModel;
use App\Customize\api\admin_v1\model\VideoSrcModel;
use Core\Lib\File;
use Core\Lib\Throwable;
use Core\Wrapper\FFmpeg;
use Core\Wrapper\FFprobe;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use function api\admin_v1\my_config;
use function api\admin_v1\res_realpath;
use function core\random;

class TestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $count = 0;
        while ($count < 180)
        {
            var_dump('当前执行时间： ' . $count++);
            sleep(1);
        }
    }
}
