<?php


namespace App\Customize\api\admin\action;

use App\Customize\api\admin\handler\VideoHandler;
use App\Customize\api\admin\job\VideoHandleJob;
use App\Customize\api\admin\model\CategoryModel;
use App\Customize\api\admin\model\VideoModel;
use App\Customize\api\admin\model\ModuleModel;
use App\Customize\api\admin\model\VideoSrcModel;
use App\Customize\api\admin\model\VideoSubjectModel;
use App\Customize\api\admin\model\UserModel;
use App\Customize\api\admin\model\VideoSubtitleModel;
use App\Customize\api\admin\util\ResourceUtil;
use App\Customize\api\admin\util\VideoUtil;
use App\Http\Controllers\api\admin\Base;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use function api\admin\get_form_error;
use function api\admin\my_config;
use function api\admin\my_config_keys;
use function api\admin\parse_order;
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
        $type_range     = my_config_keys('business.type_for_video');
        $status_range   = my_config_keys('business.status_for_video');
        $bool_range     = my_config_keys('business.bool_for_int');
        $validator = Validator::make($param , [
            'user_id'       => 'required|integer' ,
            'module_id'     => 'required|integer' ,
            'category_id'   => 'required|integer' ,
            'video_subject_id' => 'sometimes|integer' ,
            'type'          => ['required' , Rule::in($type_range)] ,
            'weight'        => 'sometimes|integer' ,
            'view_count'    => 'sometimes|integer' ,
            'praise_count'  => 'sometimes|integer' ,
            'against_count' => 'sometimes|integer' ,
            'index'         => 'sometimes|integer' ,
            'merge_video_subtitle' => ['required' , 'integer' , Rule::in($bool_range)] ,
            'status'        => ['required' , 'integer' , Rule::in($status_range)] ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }
        $video = VideoModel::find($id);
        if (empty($video)) {
            return self::error('视频记录不存在' , '' , 404);
        }
        if (!in_array($video->process_status , [-1 , 2]) && $video->src !== $param['src']) {
            return self::error('当前状态禁止更改视频源' , [
                'src' => '当前操作禁止更改视频源' ,
            ] , 403);
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('表单错误，请检查' , [
                'module_id' => '模块不存在'
            ]);
        }
        $category = CategoryModel::find($param['category_id']);
        if (empty($category)) {
            return self::error('表单错误，请检查' , [
                'category_id' => '分类不存在' ,
            ]);
        }
        $user = UserModel::find($param['user_id']);
        if (empty($user)) {
            return self::error('表单错误，请检查' , [
                'user_id' => '用户不存在'
            ]);
        }
        $video_subject = null;
        if ($param['type'] === 'pro') {
            $video_subject = VideoSubjectModel::find($param['video_subject_id']);
            if (empty($video_subject)) {
                return self::error('表单错误，请检查' , [
                    'video_subject_id' => '专题不存在' ,
                ]);
            }
            if (VideoModel::findExcludeSelfByVideoIdAndVideoSubjectIdAndIndex($video->id , $video_subject->id , $param['index'])) {
                return self::error('表单错误，请检查' , [
                    'index' => '索引已经存在' ,
                ]);
            }
        } else {
            $param['video_subject_id']  = 0;
            $param['index']             = 0;
        }

        if ($param['status'] !== '' && $param['status'] == -1 && $param['fail_reason'] === '') {
            return self::error('表单错误，请检查' , [
                'fail_reason' => '请提供失败原因' ,
            ]);
        }
        $param['weight'] = $param['weight'] === '' ? $video->weight : $param['weight'];
        $param['updated_at'] = date('Y-m-d H:i:s');
        $video_subtitles = $param['video_subtitles'] === '' ? [] : json_decode($param['video_subtitles'] , true);
        $video_has_change = $video->src !== $param['src'];
        try {
            DB::beginTransaction();
            if ($video_has_change) {
                // 视频源发生变动
                $param['process_status'] = 0;
                ResourceUtil::delete($video->src);
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
                'index' ,
                'view_count' ,
                'praise_count' ,
                'against_count' ,
                'merge_video_subtitle' ,
                'status' ,
                'fail_reason' ,
                'process_status' ,
                'updated_at' ,
            ]));
            ResourceUtil::used($param['src']);
            ResourceUtil::used($param['thumb']);
            if ($video->thumb !== $param['thumb']) {
                ResourceUtil::delete($video->thumb);
            }
            // 视频字幕
            foreach ($video_subtitles as $v)
            {
                VideoSubtitleModel::insertGetId([
                    'video_id'      => $video->id ,
                    'name'          => $v['name'] ,
                    'src'           => $v['src'] ,
                    'created_at'   => $param['created_at'] ,
                ]);
                ResourceUtil::used($v['src']);
            }
            DB::commit();
            $video_subtitle_count = VideoSubtitleModel::countByVideoId($video->id);
            if ($video_has_change ||
                ($param['merge_video_subtitle'] == 1 && $video_subtitle_count > 0)
            ) {
                VideoHandleJob::dispatch($video->id);
            }
            return self::success();
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function store(Base $context , array $param = []): array
    {
        $type_range     = my_config_keys('business.type_for_video');
        $status_range   = my_config_keys('business.status_for_video');
        $bool_range     = my_config_keys('business.bool_for_int');

        $validator = Validator::make($param , [
            'user_id'   => 'required|integer' ,
            'module_id' => 'required|integer' ,
            'category_id' => 'required|integer' ,
            'video_subject_id' => 'sometimes|integer' ,
            'type'      => ['required' , Rule::in($type_range)] ,
            'weight'    => 'sometimes|integer' ,
            'view_count'    => 'sometimes|integer' ,
            'praise_count'  => 'sometimes|integer' ,
            'against_count'  => 'sometimes|integer' ,
            'index'  => 'sometimes|integer' ,
            'status'        => ['required' , 'integer' , Rule::in($status_range)] ,
            'merge_video_subtitle' => ['required' , 'integer' , Rule::in($bool_range)] ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , get_form_error($validator));
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('表单错误，请检查' , [
                'module_id' => '模块不存在'
            ]);
        }
        $category = CategoryModel::find($param['category_id']);
        if (empty($category)) {
            return self::error('表单错误，请检查' , [
                'category_id' => '分类不存在' ,
            ]);
        }
        $user = UserModel::find($param['user_id']);
        if (empty($user)) {
            return self::error('表单错误，请检查' , [
                'user_id' => '用户不存在'
            ]);
        }
        $video_subject = null;
        if ($param['type'] === 'pro') {
            $video_subject = VideoSubjectModel::find($param['video_subject_id']);
            if (empty($video_subject)) {
                return self::error('表单错误，请检查' , [
                    'video_subject_id' => '专题不存在' ,
                ]);
            }
            if (VideoModel::findByVideoSubjectIdAndIndex($video_subject->id , $param['index'])) {
                return self::error('表单错误，请检查' , [
                    'index' => '索引已经存在' ,
                ]);
            }
        } else {
            $param['video_subject_id']  = 0;
            $param['index']             = 0;
        }
        if ($param['status'] !== '' && $param['status'] == -1 && $param['fail_reason'] === '') {
            return self::error('表单错误，请检查' , [
                'fail_reason' => '请提供失败原因' ,
            ]);
        }
        $param['weight'] = $param['weight'] === '' ? 0 : $param['weight'];
        $datetime = date('Y-m-d H:i:s');
        $param['updated_at'] = $datetime;
        $param['created_at'] = $param['created_at'] === '' ? $datetime : $param['created_at'];
        $video_subtitles = $param['video_subtitles'] === '' ? [] : json_decode($param['video_subtitles'] , true);
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
                'index' ,
                'view_count' ,
                'praise_count' ,
                'against_count' ,
                'status' ,
                'merge_video_subtitle' ,
                'fail_reason' ,
                'updated_at' ,
                'created_at' ,
            ]));
            ResourceUtil::used($param['thumb']);
            ResourceUtil::used($param['src']);
            // 视频字幕
            foreach ($video_subtitles as $v)
            {
                VideoSubtitleModel::insertGetId([
                    'video_id'      => $id ,
                    'name'          => $v['name'] ,
                    'src'           => $v['src'] ,
                    'created_at'   => $param['created_at'] ,
                ]);
                ResourceUtil::used($v['src']);
            }
            DB::commit();
            // 派发任务到队列中执行
            VideoHandleJob::dispatch($id);
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

    public static function destroyVideos(Base $context , array $video_src_ids , array $param = []): array
    {
        $res = VideoSrcModel::find($video_src_ids);
        try {
            DB::beginTransaction();
            foreach ($res as $video_src)
            {
                ResourceUtil::delete($video_src->src);
                VideoSrcModel::destroy($video_src->id);
            }
            DB::commit();
            return self::success();
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // 重新运行队列
    public static function retry(Base $context , array $ids = [] , array $param = []): array
    {
        $videos = [];
        foreach ($ids as $id)
        {
            $video = VideoModel::find($id);
            if (empty($video)) {
                return self::error('包含无效记录' , '' , 404);
            }
            if ($video->process_status !== -1) {
                return self::error('包含无效处理状态视频' , '' , 403);
            }
            $videos[$id] = $video;
        }
        foreach ($ids as $id)
        {
            $video = $videos[$id];
            VideoModel::updateById($video->id , [
                'process_status' => 0 ,
            ]);
            VideoHandleJob::dispatch($video->id);
        }
        return self::success();
    }
}
