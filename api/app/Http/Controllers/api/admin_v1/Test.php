<?php


namespace App\Http\Controllers\api\admin_v1;


use App\Customize\api\admin_v1\job\VideoHandleJob;
use Illuminate\Support\Facades\Hash;

class Test extends Base
{
    public function index()
    {
//        var_dump(Hash::make('364793'));

        VideoHandleJob::dispatch(1);

    }
}
