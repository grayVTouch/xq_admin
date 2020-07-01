<?php


namespace App\Customize\api\web_v1\action;

use App\Customize\api\web_v1\handler\CollectionGroupHandler;
use App\Customize\api\web_v1\handler\ImageSubjectHandler;
use App\Customize\api\web_v1\model\CollectionGroupModel;
use App\Customize\api\web_v1\model\CollectionModel;
use App\Customize\api\web_v1\model\ImageSubjectModel;
use App\Customize\api\web_v1\model\ModuleModel;
use App\Http\Controllers\api\web_v1\Base;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use function api\web_v1\my_config;
use function api\web_v1\user;

class UserAction extends Action
{

    public static function createCollectionGroup(Base $context , int $image_subject_id , array $param = [])
    {
        $validator = Validator::make($param , [
            'module_id' => 'required|integer' ,
            'name'      => 'required' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first());
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在');
        }
        $user = user();
        $exists = CollectionGroupModel::isExistsByModuleIdAndUserIdAndName($module->id , $user->id , $param['name']);
        if ($exists) {
            return self::error('收藏夹已经存在');
        }
        $id = CollectionGroupModel::insertGetId([
            'module_id' => $module->id ,
            'user_id' => $user->id ,
            'name' => $param['name']
        ]);
        $collection_group = CollectionGroupModel::find($id);
        $collection_group = CollectionGroupHandler::handle($collection_group);
        return self::success($collection_group);
    }

    public static function destroyCollectionGroup(Base $context , int $id , array $param = [])
    {
        $count = CollectionGroupModel::destroy($id);
        return self::success($count);
    }

    public static function destroyAllCollectionGroup(Base $context , array $ids , array $param = [])
    {
        $count = CollectionGroupModel::destroy($ids);
        return self::success($count);
    }

}
