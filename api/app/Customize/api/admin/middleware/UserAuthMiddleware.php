<?php


namespace App\Customize\api\admin\middleware;


use App\Customize\api\admin\handler\AdminHandler;
use App\Customize\api\admin\model\AdminModel;
use App\Customize\api\admin\model\AdminTokenModel;
use App\Customize\api\admin\util\PannelUtil;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function api\admin\error;

class UserAuthMiddleware
{
    public function handle(Request $request , Closure $next)
    {
        $res = $this->auth($request);
        if ($res instanceof JsonResponse) {
            return $res;
        }
        return $next($request);
    }

    public function auth(Request $request)
    {
        $token = $request->header('Authorization');
        if (empty($token)) {
            return error('Auth Failed【Empty Authorization Header】' , '' , 401);
        }
        $token = AdminTokenModel::findByToken($token);
        if (empty($token)) {
            return error('Auth Failed【Token Invalid】' , '' , 401);
        }
        $admin = AdminModel::find($token->user_id);
        if (empty($admin)) {
            return error('Auth Failed【User Not Found】' , '' , 401);
        }
        $admin = AdminHandler::handle($admin);
        app()->instance('user' , $admin);
    }
}
