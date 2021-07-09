<?php


namespace App\Customize\api\web\action;


use App\Customize\api\web\handler\VideoCompanyHandler;
use App\Customize\api\web\model\ImageProjectModel;
use App\Customize\api\web\model\ModuleModel;
use App\Customize\api\web\model\PraiseModel;
use App\Customize\api\web\model\VideoCompanyModel;
use App\Customize\api\web\model\VideoModel;
use App\Customize\api\web\model\VideoProjectModel;
use App\Customize\api\web\model\UserVideoProjectPlayRecordModel;
use App\Http\Controllers\api\web\Base;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use function api\web\my_config;
use function api\web\my_config_keys;
use function api\web\parse_order;
use function api\web\user;
use function core\current_datetime;

class VideoAction extends Action
{
    public static function incrementViewCount(Base $context , int $id , array $param = []): array
    {
        $validator = Validator::make($param , [
            'module_id' => 'required|integer' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first());
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在' , '' , 404);
        }
        $video = VideoModel::find($id);
        if (empty($video)) {
            return self::error('视频不存在' , '' , 404);
        }
        if ($video->module_id !== $module->id) {
            return self::error('当前模块不对');
        }
        try {
            DB::beginTransaction();
            if ($video->type === 'pro') {
                VideoProjectModel::incrementByIdAndColumnAndStep($video->video_project_id , 'view_count' , 1);
            }
            VideoModel::incrementByIdAndColumnAndStep($video->id , 'view_count' , 1);
            DB::commit();
            return self::success('操作成功');
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function incrementPlayCount(Base $context , int $id , array $param = []): array
    {
        $validator = Validator::make($param , [
            'module_id' => 'required|integer' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first());
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在' , '' , 404);
        }
        $video = VideoModel::find($id);
        if (empty($video)) {
            return self::error('视频不存在' , '' , 404);
        }
        if ($video->module_id !== $module->id) {
            return self::error('当前模块不对');
        }
        try {
            DB::beginTransaction();
            if ($video->type === 'pro') {
                VideoProjectModel::incrementByIdAndColumnAndStep($video->video_project_id , 'play_count' , 1);
            }
            VideoModel::incrementByIdAndColumnAndStep($video->id , 'play_count' , 1);
            DB::commit();
            return self::success('操作成功');
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function praiseHandle(Base $context , int $id , array $param = []): array
    {
        $action_range = my_config_keys('business.bool_for_int');
        $validator = Validator::make($param , [
            'module_id'     => 'required|integer' ,
            'action'        => ['required' , Rule::in($action_range)] ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , $validator->errors());
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在');
        }
        $video = VideoModel::find($id);
        if (empty($video)) {
            return self::error('视频不存在' , '' , 404);
        }
        $datetime = date('Y-m-d H:i:s');
        $user = user();
        if ($video->type === 'pro') {
            // 专题视频
            $relation_type = 'video_project';
            $relation_id = $video->video_project_id;
        } else {
            // 杂项视频
            $relation_type = 'video';
            $relation_id = $video->id;
        }
        try {
            DB::beginTransaction();
            // 视频专题
            if ($param['action'] == 1) {
                $praise = PraiseModel::findByModuleIdAndUserIdAndRelationTypeAndRelationId($module->id , $user->id , $relation_type , $relation_id);
                if (empty($praise)) {
                    PraiseModel::insertOrIgnore([
                        'module_id' => $module->id ,
                        'user_id' => $user->id ,
                        'relation_type' => $relation_type ,
                        'relation_id' => $relation_id ,
                        'created_at' => $datetime
                    ]);
                }
                VideoModel::incrementByIdAndColumnAndStep($video->id , 'praise_count' , 1);
                if ($relation_type === 'video_project') {
                    VideoProjectModel::incrementByIdAndColumnAndStep($relation_id , 'praise_count' , 1);
                }
            } else {
                PraiseModel::delByModuleIdAndUserIdAndRelationTypeAndRelationId($module->id , $user->id , $relation_type , $relation_id);
                VideoModel::decrementByIdAndColumnAndStep($video->id , 'praise_count' , 1);
                if ($relation_type === 'video_project') {
                    VideoProjectModel::decrementByIdAndColumnAndStep($relation_id , 'praise_count' , 1);
                }
            }
            DB::commit();
            return self::success('操作成功');
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function record(Base $context , int $id , array $param = []): array
    {
        $validator = Validator::make($param , [
            'module_id' => 'required|integer' ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first());
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在' , '' , 404);
        }
        $video = VideoModel::find($id);
        if (empty($video)) {
            return self::error('视频不存在' , '' , 404);
        }
        if ($video->module_id !== $module->id) {
            return self::error('当前模块不对');
        }
        if ($video->type === 'pro' && $param['index'] === '') {
            return self::error('视频索引尚未提供');
        }
        $timestamp = time();
        $datetime = date('Y-m-d H:i:s' , $timestamp);
        $param['played_duration'] = empty($param['played_duration']) ? 0 : $param['played_duration'];
        $param['ratio'] = number_format($param['played_duration'] / $video->duration , 2);
        try {
            if ($video->type === 'pro') {
                $user_video_project_play_record = UserVideoProjectPlayRecordModel::findByModuleIdAndUserIdAndVideoProjectId($module->id , user()->id , $video->video_project_id);
                if (empty($user_video_project_play_record)) {
                    UserVideoProjectPlayRecordModel::insertOrIgnore([
                        'module_id' => $module->id ,
                        'user_id' => user()->id ,
                        'video_project_id' => $video->video_project_id ,
                        'video_id' => $video->id ,
                        'index' => $param['index'] ,
                        'played_duration' => $param['played_duration'] ,
                        'definition' => $param['definition'] ,
                        'subtitle' => $param['subtitle'] ,
                        'ratio' => $param['ratio'] ,
                        'volume' => $param['volume'] ,
                        'date' => date('Y-m-d' , $timestamp) ,
                        'time' => date('H:i:s' , $timestamp) ,
                        'datetime' => date('Y-m-d H:i:s' , $timestamp) ,
                        'created_at' => $datetime ,
                    ]);
                } else {
                    UserVideoProjectPlayRecordModel::updateById($user_video_project_play_record->id , [
                        'video_id' => $video->id ,
                        'index' => $param['index'] ,
                        'played_duration' => $param['played_duration'] ,
                        'ratio' => $param['ratio'] ,
                        'volume' => $param['volume'] ,
                        'definition' => $param['definition'] ,
                        'subtitle' => $param['subtitle'] ,
                        'date' => date('Y-m-d' , $timestamp) ,
                        'time' => date('H:i:s' , $timestamp) ,
                        'datetime' => date('Y-m-d H:i:s' , $timestamp) ,
                    ]);
                }
            } else {
                // 非视频专题
            }
            return self::success('操作成功');
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
