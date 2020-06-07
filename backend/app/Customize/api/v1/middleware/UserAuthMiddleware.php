<?php


namespace App\Customize\api\v1\middleware;


use App\Customize\api\v1\model\AdminModel;
use App\Customize\api\v1\model\AdminTokenModel;
use App\Customize\api\v1\util\UserUtil;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function api\v1\error;

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
        $user = AdminModel::findById($token->user_id);
        if (empty($user)) {
            return error('Auth Failed【User Not Found】' , 401);
        }
        UserUtil::handle($user);
        app()->instance('user' , $user);
    }
}
