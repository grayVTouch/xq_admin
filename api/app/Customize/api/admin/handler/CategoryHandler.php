<?php


namespace App\Customize\api\admin\handler;


use App\Customize\api\admin\model\CategoryModel;
use App\Customize\api\admin\model\Model;
use App\Customize\api\admin\model\ModuleModel;
use App\Customize\api\admin\model\UserModel;
use stdClass;
use Traversable;
use function api\admin\get_config_key_mapping_value;
use function core\convert_object;

class CategoryHandler extends Handler
{
    public static function handle(?Model $model , array $with = [] , bool $deep = true): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $model = convert_object($model);

        $model->__type__ = get_config_key_mapping_value('business.category_type' , $model->type);
        $model->__status__  = get_config_key_mapping_value('business.status_for_category' , $model->status);
        $model->__is_enabled__  = get_config_key_mapping_value('business.bool_for_int' , $model->is_enabled);

        if (in_array('module' , $with)) {
            $module = ModuleModel::find($model->module_id);
            $module = ModuleHandler::handle($module);
            $model->module = $module;
        }

        if (in_array('parent' , $with)) {
            if ($deep) {
                $category = $model->p_id ? CategoryModel::find($model->p_id) : null;
                $category = self::handle($category , $with ,false);
            } else {
                $category = null;
            }
            $model->parent = $category;
        }

        if (in_array('user' , $with)) {
            $user = UserModel::find($model->user_id);
            $user = UserHandler::handle($user);
            $model->user = $user;
        }


        return $model;
    }


}
