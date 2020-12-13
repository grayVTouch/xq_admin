<?php


namespace App\Customize\api\admin\handler;


use App\Customize\api\admin\model\Model;
use App\Customize\api\admin\model\ModuleModel;
use stdClass;
use function api\admin\get_config_key_mapping_value;

use function core\convert_object;

class NavHandler extends Handler
{
    public static function handle(?Model $model , array $with = [] , bool $deep = true): ?stdClass
    {
        if (empty($model)) {
            return null;
        }

        $model = convert_object($model);

        $model->__is_enabled__ = get_config_key_mapping_value('business.bool_for_int' , $model->is_enabled);
        $model->__type__ = get_config_key_mapping_value('business.type_for_nav' , $model->type);

        if (in_array('module' , $with)) {
            $module = ModuleModel::find($model->module_id);
            $module = ModuleHandler::handle($module);
            $model->module = $module;
        }

        if (in_array('parent' , $with)) {
            if ($deep) {
                $nav = $model->p_id ? Model::find($model->p_id) : null;
                $nav = self::handle($nav , $with , false);
            } else {
                $nav = null;
            }
            $model->parent = $nav;
        }

        return $model;
    }


}
