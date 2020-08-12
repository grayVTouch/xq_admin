<?php


namespace App\Http\Controllers\api\admin_v1;


use App\Customize\api\admin_v1\job\RestartFaildJob;
use App\Customize\api\admin_v1\job\TestJob;
use App\Customize\api\admin_v1\job\VideoHandleJob;
use Exception;
use Illuminate\Support\Facades\Hash;

class Test extends Base
{
    public function index()
    {
//        var_dump(env('APP_URL'));
        var_dump(env('ADMIN_USERNAME'));
//        $job_id = RestartFaildJob::dispatch(1);
//        var_dump($job_id);

//        VideoHandleJob::dispatch(1);
//        TestJob::dispatch();

    }
}
