<?php


namespace App\Customize\api\web_v1\middleware;


use App\Customize\api\web_v1\handler\UserHandler;
use App\Customize\api\web_v1\model\UserModel;
use App\Customize\api\web_v1\model\UserTokenModel;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use function api\web_v1\error;

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
            return error('Auth Failed【Empty Authorization Header】' , '' ,401);
        }
        $token = UserTokenModel::findByToken($token);
        if (empty($token)) {
            return error('Auth Failed【Token Invalid】' , '' ,401);
        }
        $user = UserModel::find($token->user_id);
        if (empty($user)) {
            return error('Auth Failed【User Not Found】' , '' ,401);
        }
        $user = UserHandler::handle($user);
        app()->instance('user' , $user);
    }
}
