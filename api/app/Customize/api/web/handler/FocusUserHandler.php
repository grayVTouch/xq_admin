<?php


namespace App\Customize\api\web\handler;


use App\Customize\api\web\model\CollectionModel;
use App\Customize\api\web\model\PraiseModel;
use App\Customize\api\web\model\FocusUserModel;
use App\Customize\api\web\model\UserModel;
use App\Customize\api\web\util\FileUtil;
use stdClass;
use function api\web\get_value;
use function api\web\user;
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
