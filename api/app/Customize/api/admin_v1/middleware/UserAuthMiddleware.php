<?php


namespace App\Customize\api\admin_v1\middleware;


use App\Customize\api\admin_v1\handler\AdminHandler;
use App\Customize\api\admin_v1\model\AdminModel;
use App\Customize\api\admin_v1\model\AdminTokenModel;
use App\Customize\api\admin_v1\util\UserUtil;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function api\admin_v1\error;

class UserAuthMiddleware
{
    public function handle(Request $request , Closure $next)
    {
        $res = $this->auth($request);
        if ($res instanceof Response) {
            return $res;
        }
        return $next($request);
    }

    public function auth(Request $request)
    {
        $token = $request->header('Authorization');
        if (empty($token)) {
            return error('Auth Failed【Empty Authorization Header】' , 401);
        }
        $token = AdminTokenModel::findByToken($token);
        if (empty($token)) {
            return error('Auth Failed【Token Invalid】' , 401);
        }
        $admin = AdminModel::find($token->user_id);
        if (empty($admin)) {
            return error('Auth Failed【User Not Found】' , 401);
        }
        $admin = AdminHandler::handle($admin);
        app()->instance('user' , $admin);
    }
}
