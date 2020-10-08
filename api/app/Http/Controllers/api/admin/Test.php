<?php


namespace App\Http\Controllers\api\admin;


use App\Customize\api\admin\job\QueueHandleJob;
use App\Customize\api\admin\job\TestJob;
use App\Customize\api\admin\job\VideoHandleJob;
use Exception;
use Illuminate\Support\Facades\Hash;

class Test extends Base
{
    public function index()
    {
//        var_dump(env('APP_URL'));
//        var_dump(env('ADMIN_USERNAME'));
//        $job_id = RestartFaildJob::dispatch(1);
//        var_dump($job_id);

//        VideoHandleJob::dispatch(1);
//        TestJob::dispatch();

//        return response('fuck' , 500);
//        throw new Exception('exception');
        $i = 0;
        while ($i < 3)
        {
            $i++;
            sleep(1);
        }

        return response('fuck' , 400);
    }
}
