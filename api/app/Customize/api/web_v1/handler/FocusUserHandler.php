<?php


namespace App\Customize\api\web_v1\handler;


use App\Customize\api\web_v1\model\CollectionModel;
use App\Customize\api\web_v1\model\PraiseModel;
use App\Customize\api\web_v1\model\FocusUserModel;
use App\Customize\api\web_v1\model\UserModel;
use Illuminate\Support\Facades\Storage;
use stdClass;
use function api\web_v1\get_value;
use function api\web_v1\user;
use function core\convert_obj;

class FocusUserHandler extends Handler
{
    public static function handle(?FocusUserModel $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_obj($model);

        $user = UserModel::find($res->user_id);
        $user = UserHandler::handle($user);

        $focus_user = UserModel::find($res->focus_user_id);
        $focus_user = UserHandler::handle($focus_user);

        $res->user = $user;
        $res->focus_user = $focus_user;

        return $res;
    }

}
