<?php


namespace App\Customize\api\admin_v1\handler;


use App\Customize\api\admin_v1\model\CategoryModel;
use App\Customize\api\admin_v1\model\ImageModel;
use App\Customize\api\admin_v1\model\ModuleModel;
use App\Customize\api\admin_v1\model\RelationTagModel;
use App\Customize\api\admin_v1\model\SubjectModel;
use App\Customize\api\admin_v1\model\UserModel;
use App\Customize\api\admin_v1\model\VideoModel;
use Illuminate\Support\Facades\Storage;
use stdClass;
use function api\admin_v1\get_value;
use function core\convert_obj;

class VideoHandler extends Handler
{
    public static function handle(?VideoModel $model): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_obj($model);

        $user = UserModel::find($res->user_id);
        $user = UserHandler::handle($user);

        $module = ModuleModel::find($res->module_id);
        $module = ModuleHandler::handle($module);

        $category = CategoryModel::find($res->category_id);
        $category = CategoryHandler::handle($category);

        if ($res->type === 'pro') {
            $subject = SubjectModel::find($res->subject_id);
            $subject = SubjectHandler::handle($subject);
        } else {
            $subject = null;
        }

        $images = ImageModel::getByImageSubjectId($res->id);
        $images = ImageHandler::handleAll($images);

        $tags = RelationTagModel::getByRelationTypeAndRelationId('image_subject' , $res->id);

        $res->user = $user;
        $res->module = $module;
        $res->category = $category;
        $res->subject = $subject;

        $res->images = $images;
        $res->tags = $tags;

        $res->__thumb__ = empty($res->thumb) ? '' : Storage::url($res->thumb);
        $res->__type__ = get_value('business.type_for_image_subject' , $res->type);
        $res->__status__ = get_value('business.status_for_image_subject' , $res->status);
        return $res;
    }

}
