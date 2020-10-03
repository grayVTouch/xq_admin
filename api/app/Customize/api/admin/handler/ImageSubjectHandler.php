<?php


namespace App\Customize\api\admin\handler;


use App\Customize\api\admin\model\CategoryModel;
use App\Customize\api\admin\model\ImageModel;
use App\Customize\api\admin\model\ImageSubjectModel;
use App\Customize\api\admin\model\ModuleModel;
use App\Customize\api\admin\model\RelationTagModel;
use App\Customize\api\admin\model\SubjectModel;
use App\Customize\api\admin\model\UserModel;
use App\Customize\api\admin\util\FileUtil;
use App\Customize\api\admin\model\Model;
use stdClass;
use function api\admin\get_config_key_mapping_value;

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


        $res->__type__ = get_config_key_mapping_value('business.type_for_image_subject' , $res->type);
        $res->__status__ = get_config_key_mapping_value('business.status_for_image_subject' , $res->status);
        return $res;
    }

}
