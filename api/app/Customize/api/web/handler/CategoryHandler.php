<?php


namespace App\Customize\api\web\handler;


use App\Customize\api\web\model\CategoryModel;
use App\Customize\api\web\model\Model;
use App\Customize\api\web\model\ModuleModel;
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
        $model = convert_object($model);

        if (in_array('category' , $with)) {
            if ($deep) {
                $category = $model->p_id ? CategoryModel::find($model->p_id) : null;
                $category = self::handle($category , $with , false);
            } else {
                $category = null;
            }
            $model->category = $category;
        }

        return $model;
    }

    // 附加：上级
    public static function parent($model): void
    {
        if (empty($model)) {
            return ;
        }
        $parent = $model->p_id ? CategoryModel::find($model->p_id) : null;
        $parent = self::handle($parent);
        $model->parent = $parent;
    }

}
