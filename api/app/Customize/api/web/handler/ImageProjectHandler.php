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
use Exception;
use stdClass;
use function api\web\get_config_key_mapping_value;
use function api\web\get_value;
use function api\web\user;
use function core\convert_object;

class ImageProjectHandler extends Handler
{
    /**
     * @param Model|null $model
     * @param array $with
     * @return stdClass|null
     * @throws Exception
     */
    public static function handle(?Model $model , array $with = []): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_object($model);

        $res->__type__      = get_config_key_mapping_value('business.type_for_image_project', $res->type);
        $res->__status__    = get_config_key_mapping_value('business.status_for_image_project' , $res->status);

        // 针对当前用户的一些字段
        // 是否收藏
        if (empty(user())) {
            $res->collected = 0;
            $res->praised = 0;
        } else {
            $res->collected = CollectionModel::findByModuleIdAndUserIdAndRelationTypeAndRelationId($res->module_id , user()->id , 'image_project' , $res->id) ? 1 : 0;
            $res->praised = PraiseModel::findByModuleIdAndUserIdAndRelationTypeAndRelationId($res->module_id , user()->id , 'image_project' , $res->id) ? 1 : 0;
        }

        // 收藏数量
        $res->collect_count = CollectionModel::countByModuleIdAndRelationTypeAndRelationId($res->module_id , 'image_project' , $res->id);

        $res->format_time = date('Y-m-d H:i' , strtotime($res->created_at));

        if (in_array('user' , $with)) {
            $user = UserModel::find($res->user_id);
            $user = UserHandler::handle($user);
            $res->user = $user;
        }

        if (in_array('module' , $with)) {
            $module = ModuleModel::find($res->module_id);
            $module = ModuleHandler::handle($module);

            $res->module = $module;
        }

        if (in_array('category' , $with)) {
            $category = CategoryModel::find($res->category_id);
            $category = CategoryHandler::handle($category);
            $res->category = $category;
        }

        if (in_array('image_subject' , $with)) {
            if ($res->type === 'pro') {
                $image_subject = ImageSubjectModel::find($res->image_subject_id);
                $image_subject = ImageSubjectHandler::handle($image_subject);
            } else {
                $image_subject = null;
            }
            $res->image_subject = $image_subject;
        }

        if (in_array('images' , $with)) {
            $images = ImageModel::getByImageProjectId($res->id);
            $images = ImageHandler::handleAll($images);

            $res->images = $images;
        }

        if (in_array('tags' , $with)) {
            $tags = RelationTagModel::getByRelationTypeAndRelationId('image_project' , $res->id);
            $res->images = $images;
            $res->tags = $tags;
        }

        return $res;
    }

}
