<?php


namespace App\Customize\api\admin\handler;


use App\Customize\api\admin\model\CategoryModel;
use App\Customize\api\admin\model\ModuleModel;
use stdClass;
use Traversable;
use function core\convert_obj;

class CategoryHandler extends Handler
{
    public static function handle(?CategoryModel $model , bool $deep = true): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_obj($model);
        if ($deep) {
            $category = $res->p_id ? CategoryModel::find($res->p_id) : null;
            $category = self::handle($category , false);
        } else {
            $category = null;
        }
        $module = ModuleModel::find($res->module_id);
        ModuleHandler::handle($module);

        $res->module = $module;
        $res->category = $category;
        return $res;
    }


}
