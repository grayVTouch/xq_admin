<?php


namespace App\Customize\api\admin_v1\middleware;


use Closure;
use Illuminate\Http\Request;

class CustomizeMiddleware
{
    public function handle(Request $request , Closure $next)
    {
        $this->resoveDependencies();
        return $next($request);
    }

    // 解决依赖
    public function resoveDependencies()
    {
        //
        require_once __DIR__ . '/../common/common.php';

        require_once __DIR__ . '/../plugin/extra/app.php';
    }
}
