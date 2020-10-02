<?php


namespace App\Customize\api\web\middleware;


use App\Customize\api\web\handler\UserHandler;
use App\Customize\api\web\model\UserModel;
use App\Customize\api\web\model\UserTokenModel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function api\web\error;

class UserMiddleware
{
    public function handle(Request $request , Closure $next)
    {
        // 获取用户信息如果有的话
        $this->getUser($request);
        return $next($request);
    }

    public function getUser(Request $request)
    {
        $token = $request->header('Authorization');
        if (empty($token)) {
            return ;
        }
        $token = UserTokenModel::findByToken($token);
        if (empty($token)) {
            return ;
        }
        $user = UserModel::find($token->user_id);
        if (empty($user)) {
            return ;
        }
        $user = UserHandler::handle($user);
        app()->instance('user' , $user);
    }
}
