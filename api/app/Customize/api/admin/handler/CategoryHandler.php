<?php


namespace App\Customize\api\admin\handler;


use App\Customize\api\admin\model\CategoryModel;
use App\Customize\api\admin\model\Model;
use App\Customize\api\admin\model\ModuleModel;
use stdClass;
use Traversable;
use function core\convert_object;

class CategoryHandler extends Handler
{
    public static function handle(?Model $model , array $with = [] , bool $deep = true): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $res = convert_object($model);
        if ($deep) {
            $category = $res->p_id ? CategoryModel::find($res->p_id) : null;
            $category = self::handle($category , $with ,false);
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
