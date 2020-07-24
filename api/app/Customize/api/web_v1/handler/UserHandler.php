<?php


namespace App\Customize\api\web_v1\handler;


use App\Customize\api\web_v1\model\CollectionModel;
use App\Customize\api\web_v1\model\FocusUserModel;
use App\Customize\api\web_v1\model\PraiseModel;
use App\Customize\api\web_v1\model\TagModel;
use App\Customize\api\web_v1\model\UserModel;
use Illuminate\Support\Facades\Storage;
use stdClass;
use function api\web_v1\get_value;
use function api\web_v1\user;
use function core\convert_obj;

class UserHandler extends Handler
{
    public static function handle(?UserModel $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_obj($model);

        // 我关注的人数量（关注数）
        $res->my_focus_user_count = FocusUserModel::countByUserId($res->id);
        // 关注我的人数量（粉丝数）
        $res->focus_me_user_count = FocusUserModel::countByFocusUserId($res->id);
        // 点赞数（我点赞的数量）
        $res->praise_count = PraiseModel::countByUserId($res->id);
        // 收藏数（收藏数量）
        $res->collect_count = CollectionModel::countByUserId($res->id);
        // 当前登录用户
        $user = user();

        if (!empty($user)) {
            if ($user->id === $res->id) {
                $res->focused = 0;
            } else {
                $res->focused = FocusUserModel::findByUserIdAndFocusUserId($user->id , $res->id) ? 1 : 0;
            }
        } else {
            $res->focused = 0;
        }

        $res->__channel_thumb__ = empty($res->channel_thumb) ? '' : Storage::url($res->channel_thumb);
        $res->__avatar__ = empty($res->avatar) ? '' : Storage::url($res->avatar);
        $res->__sex__ = get_value('business.sex_for_user' , $res->sex);

        return $res;
    }

}
