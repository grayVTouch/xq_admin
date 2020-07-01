<?php


namespace App\Customize\api\web_v1\action;

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

class ImageSubjectWithAuthAction extends Action
{

    //
    public static function collectionHandle(Base $context , int $image_subject_id , array $param = [])
    {
        $action_range = my_config('business.action_for_collection');
        $validator = Validator::make($param , [
            'module_id'             => 'required|integer' ,
            'collection_group_id'   => 'required|integer' ,
            'action'                => ['required' , Rule::in($action_range)] ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first());
        }
        $image_subject = ImageSubjectModel::find($image_subject_id);
        if (empty($module)) {
            return self::error('图片专题不存在');
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在');
        }
        $collection_group = CollectionGroupModel::find($param['collection_group_id']);
        if (empty($collection_group)) {
            return self::error('收藏夹不存在');
        }
        $user = user();
        if ($param['action'] === 'collect') {
            // 收藏
            $id = CollectionModel::insertGetId([
                'module_id' => $module->id ,
                'user_id' => $user->id ,
                'collection_group_id' => $collection_group->id ,
                'relation_table' => 'xq_image_subject' ,
                'relation_id' => $image_subject->id
            ]);
            return self::success($id);
        }
        // 取消收藏
        $count= CollectionModel::delByModuleIdAndUserIdAndRelationTableAndRelationId($module->id , $user->id , 'xq_image_subject' , $image_subject->id);
        return self::success($count);
    }
}
