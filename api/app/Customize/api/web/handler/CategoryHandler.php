<?php


namespace App\Customize\api\web\handler;


use App\Customize\api\web\model\CategoryModel;
use stdClass;
use Traversable;
use function core\convert_object;

class CategoryHandler extends Handler
{
    public static function handle(?CategoryModel $model , bool $deep = true): ?stdClass
    {
        if (empty($model)) {
            return null;
        }
        $model = convert_object($model);
        if ($deep) {
            $category = $model->p_id ? CategoryModel::find($model->p_id) : null;
            $category = self::handle($category , false);
        } else {
            $category = null;
        }
        $model->category = $category;
        return $model;
    }
}
