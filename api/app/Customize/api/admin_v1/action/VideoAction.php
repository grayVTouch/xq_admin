<?php


namespace App\Customize\api\admin_v1\action;

use App\Customize\api\admin_v1\handler\VideoHandler;
use App\Customize\api\admin_v1\job\VideoHandleJob;
use App\Customize\api\admin_v1\model\CategoryModel;
use App\Customize\api\admin_v1\model\VideoModel;
use App\Customize\api\admin_v1\model\ModuleModel;
use App\Customize\api\admin_v1\model\VideoSrcModel;
use App\Customize\api\admin_v1\model\VideoSubjectModel;
use App\Customize\api\admin_v1\model\UserModel;
use App\Customize\api\admin_v1\util\VideoUtil;
use App\Http\Controllers\api\admin_v1\Base;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use function api\admin_v1\get_form_error;
use function api\admin_v1\my_config;
use function api\admin_v1\parse_order;
use function core\array_unit;

class VideoAction extends Action
{
    public static function index(Base $context , array $param = []): array
    {
        $order = $param['order'] === '' ? [] : parse_order($param['order'] , '|');
        $limit = $param['limit'] === '' ? my_config('app.limit') : $param['limit'];
        $paginator = VideoModel::index($param , $order , $limit);
        $paginator = VideoHandler::handlePaginator($paginator);
        return self::success('' , $paginator);
    }

    public static function update(Base $context , $id , array $param = []): array
    {
        $type_range = array_keys(my_config('business.type_for_video'));
        $status_range = array_keys(my_config('business.status_for_video'));
        $validator = Validator::make($param , [
            'name'      => 'required' ,
            'user_id'   => 'required|integer' ,
            'module_id' => 'required|integer' ,
            'category_id' => 'required|integer' ,
            'video_subject_id' => 'sometimes|integer' ,
            'type'      => ['required' , Rule::in($type_range)] ,
            'weight'    => 'sometimes|integer' ,
            'view_count'    => 'sometimes|integer' ,
            'praise_count'  => 'sometimes|integer' ,
            'against_count'  => 'sometimes|integer' ,
            'status'        => ['required' , 'integer' , Rule::in($status_range)] ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }
        $video = VideoModel::find($id);
        if (empty($video)) {
            return self::error('视频记录不存在' , '' , 404);
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error($validator->errors()->first() , [
                'module_id' => '模块不存在'
            ]);
        }
        $category = CategoryModel::find($param['category_id']);
        if (empty($category)) {
            return self::error($validator->errors()->first() , [
                'category_id' => '分类不存在' ,
            ]);
        }
        $user = UserModel::find($param['user_id']);
        if (empty($user)) {
            return self::error($validator->errors()->first() , [
                'user_id' => '用户不存在'
            ]);
        }
        $video_subject = null;
        if ($param['type'] === 'pro') {
            $video_subject = VideoSubjectModel::find($param['video_subject_id']);
            if (empty($video_subject)) {
                return self::error($validator->errors()->first() , [
                    'video_subject_id' => '专题不存在' ,
                ]);
            }
        }
        if ($param['status'] !== '' && $param['status'] == -1 && $param['fail_reason'] === '') {
            return self::error($validator->errors()->first() , [
                'fail_reason' => '请提供失败原因' ,
            ]);
        }
        $param['weight'] = $param['weight'] === '' ? $video->weight : $param['weight'];
        $param['update_time'] = date('Y-m-d H:i:s');
        $param['create_time'] = $param['create_time'] === '' ? $video->create_time : $param['create_time'];
        $has_change = $video->src !== $param['src'];
        try {
            DB::beginTransaction();
            if ($has_change) {
                // 视频源发生变动
                $param['process_status'] = 0;
            }
            VideoModel::updateById($video->id , array_unit($param , [
                'name' ,
                'user_id' ,
                'module_id' ,
                'category_id' ,
                'src' ,
                'type' ,
                'video_subject_id' ,
                'thumb' ,
                'description' ,
                'weight' ,
                'view_count' ,
                'praise_count' ,
                'against_count' ,
                'status' ,
                'fail_reason' ,
                'process_status' ,
                'update_time' ,
                'create_time' ,
            ]));
            if ($has_change) {
                // 视频源发生变化
                VideoSrcModel::delByVideoId($video->id);
                VideoHandleJob::dispatch($video->id);
            }
            DB::commit();
            return self::success();
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function store(Base $context , array $param = []): array
    {
        $type_range = array_keys(my_config('business.type_for_video'));
        $status_range = array_keys(my_config('business.status_for_video'));
        $validator = Validator::make($param , [
            'name'      => 'required' ,
            'user_id'   => 'required|integer' ,
            'module_id' => 'required|integer' ,
            'category_id' => 'required|integer' ,
            'video_subject_id' => 'sometimes|integer' ,
            'type'      => ['required' , Rule::in($type_range)] ,
            'weight'    => 'sometimes|integer' ,
            'view_count'    => 'sometimes|integer' ,
            'praise_count'  => 'sometimes|integer' ,
            'against_count'  => 'sometimes|integer' ,
            'status'        => ['required' , 'integer' , Rule::in($status_range)] ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error($validator->errors()->first() , [
                'module_id' => '模块不存在'
            ]);
        }
        $category = CategoryModel::find($param['category_id']);
        if (empty($category)) {
            return self::error($validator->errors()->first() , [
                'category_id' => '分类不存在' ,
            ]);
        }
        $user = UserModel::find($param['user_id']);
        if (empty($user)) {
            return self::error($validator->errors()->first() , [
                'user_id' => '用户不存在'
            ]);
        }
        $video_subject = null;
        if ($param['type'] === 'pro') {
            $video_subject = VideoSubjectModel::find($param['video_subject_id']);
            if (empty($video_subject)) {
                return self::error($validator->errors()->first() , [
                    'video_subject_id' => '专题不存在' ,
                ]);
            }
        }
        if ($param['status'] !== '' && $param['status'] == -1 && $param['fail_reason'] === '') {
            return self::error($validator->errors()->first() , [
                'fail_reason' => '请提供失败原因' ,
            ]);
        }
        $param['weight'] = $param['weight'] === '' ? 0 : $param['weight'];
        $datetime = date('Y-m-d H:i:s');
        $param['update_time'] = $datetime;
        $param['create_time'] = $param['create_time'] === '' ? $datetime : $param['create_time'];
        try {
            DB::beginTransaction();
            $id = VideoModel::insertGetId(array_unit($param , [
                'name' ,
                'user_id' ,
                'module_id' ,
                'category_id' ,
                'src' ,
                'type' ,
                'video_subject_id' ,
                'thumb' ,
                'description' ,
                'weight' ,
                'view_count' ,
                'praise_count' ,
                'against_count' ,
                'status' ,
                'fail_reason' ,
                'update_time' ,
                'create_time' ,
            ]));
            // 派发任务到队列中执行（如果 redis 异常，那么可以进行回滚，避免数据错误）
            VideoHandleJob::dispatch($id);
            DB::commit();
            return self::success();
        } catch(Exception $e) {
            throw $e;
        }
    }

    public static function show(Base $context , $id , array $param = []): array
    {
        $res = VideoModel::find($id);
        if (empty($res)) {
            return self::error('视频不存在' , '' , 404);
        }
        $res = VideoHandler::handle($res);
        return self::success('' , $res);
    }

    public static function destroy(Base $context , $id , array $param = []): array
    {
        try {
            DB::beginTransaction();
            VideoUtil::delete($id);
            DB::commit();
            return self::success();
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function destroyAll(Base $context , array $ids , array $param = []): array
    {
        try {
            DB::beginTransaction();
            foreach ($ids as $id)
            {
                VideoUtil::delete($id);
            }
            DB::commit();
            return self::success();
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
