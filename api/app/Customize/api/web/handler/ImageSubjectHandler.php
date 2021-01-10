<?php


namespace App\Customize\api\web\handler;


use App\Customize\api\web\model\CollectionModel;
use App\Customize\api\web\model\PraiseModel;
use App\Customize\api\web\model\RelationTagModel;
use App\Customize\api\web\model\CategoryModel;
use App\Customize\api\web\model\ImageModel;
use App\Customize\api\web\model\ImageProjectModel;
use App\Customize\api\web\model\ModuleModel;
use App\Customize\api\web\model\ImageSubjectModel;
use App\Customize\api\web\model\UserModel;
use App\Customize\api\web\util\FileUtil;
use App\Customize\api\web\model\Model;
use stdClass;
use function api\web\get_config_key_mapping_value;
use function api\web\get_value;
use function api\web\user;
use function core\convert_object;

class ImageSubjectHandler extends Handler
{
    public static function handle(?Model $model , array $with = []): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_object($model);

        $user = UserModel::find($res->user_id);
        $user = UserHandler::handle($user);

        $module = ModuleModel::find($res->module_id);
        $module = ModuleHandler::handle($module);

        $category = CategoryModel::find($res->category_id);
        $category = CategoryHandler::handle($category);

        if ($res->type === 'pro') {
            $subject = ImageSubjectModel::find($res->image_subject_id);
            $subject = SubjectHandler::handle($subject);
        } else {
            $subject = null;
        }

        $images = ImageModel::getByImageProjectId($res->id);
        $images = ImageHandler::handleAll($images);

        $tags = RelationTagModel::getByRelationTypeAndRelationId('image_project' , $res->id);

        $res->user = $user;
        $res->module = $module;
        $res->category = $category;
        $res->subject = $subject;
        $res->tags = $tags;
        $res->images = $images;


        try {
            $res->__type__ = get_config_key_mapping_value('business.type_for_image_project', $res->type);
        } catch (\Exception $e) {
        }
        $res->__status__ = get_config_key_mapping_value('business.status_for_image_project' , $res->status);

        // 针对当前用户的一些字段
        // 是否收藏
        $user = user();
        if (empty($user)) {
            $res->collected = 0;
            $res->praised = 0;
        } else {
            $res->collected = CollectionModel::findByModuleIdAndUserIdAndRelationTypeAndRelationId($res->module_id , $user->id , 'image_project' , $res->id) ? 1 : 0;
            $res->praised = PraiseModel::findByModuleIdAndUserIdAndRelationTypeAndRelationId($res->module_id , $user->id , 'image_project' , $res->id) ? 1 : 0;
        }

        // 收藏数量
        $res->collect_count = CollectionModel::countByModuleIdAndRelationTypeAndRelationId($res->module_id , 'image_project' , $res->id);

        $res->format_time = date('Y-m-d H:i' , strtotime($res->created_at));

        return $res;
    }

}
