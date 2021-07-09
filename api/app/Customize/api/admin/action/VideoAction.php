<?php


namespace App\Customize\api\admin\action;

use App\Customize\api\admin\handler\VideoHandler;
use App\Customize\api\admin\job\VideoHandleJob;
use App\Customize\api\admin\job\VideoResourceHandleJob;
use App\Customize\api\admin\model\CategoryModel;
use App\Customize\api\admin\model\VideoModel;
use App\Customize\api\admin\model\ModuleModel;
use App\Customize\api\admin\model\VideoSrcModel;
use App\Customize\api\admin\model\VideoProjectModel;
use App\Customize\api\admin\model\UserModel;
use App\Customize\api\admin\model\VideoSubtitleModel;
use App\Customize\api\admin\util\FileUtil;
use App\Customize\api\admin\util\ResourceUtil;
use App\Customize\api\admin\util\VideoUtil;
use App\Http\Controllers\api\admin\Base;
use Core\Lib\File;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use function api\admin\get_form_error;
use function api\admin\my_config;
use function api\admin\my_config_keys;
use function api\admin\parse_order;
use function core\array_unit;
use function core\current_datetime;
use function core\object_to_array;

class VideoAction extends Action
{
    public static function index(Base $context , array $param = []): array
    {
        $order = $param['order'] === '' ? [] : parse_order($param['order'] , '|');
        $size = $param['size'] === '' ? my_config('app.limit') : $param['size'];
        $res = VideoModel::index($param , $order , $size);
        $res = VideoHandler::handlePaginator($res);
        foreach ($res->data as $v)
        {
            // 附加：模块
            VideoHandler::module($v);
            // 附加：用户
            VideoHandler::user($v);
            // 附加：分类
            VideoHandler::category($v);
            // 附加：视频专题
            VideoHandler::videoProject($v);
            // 附加：视频
            VideoHandler::videos($v);
            // 附加：视频字幕
            VideoHandler::videoSubtitles($v);
        }

        return self::success('' , $res);
    }

    public static function update(Base $context , $id , array $param = []): array
    {
        $type_range     = my_config_keys('business.type_for_video');
        $status_range   = my_config_keys('business.status_for_video');
        $bool_range     = my_config_keys('business.bool_for_int');
        $validator = Validator::make($param , [
            'user_id'       => 'required|integer' ,
            'module_id'     => 'required|integer' ,
            'category_id'   => 'sometimes|integer' ,
            'video_project_id' => 'sometimes|integer' ,
            'type'          => ['required' , Rule::in($type_range)] ,
            'weight'        => 'sometimes|integer' ,
            'view_count'    => 'sometimes|integer' ,
            'play_count'    => 'sometimes|integer' ,
            'praise_count'  => 'sometimes|integer' ,
            'against_count' => 'sometimes|integer' ,
            'index'         => 'sometimes|integer' ,
            'merge_video_subtitle' => ['required' , 'integer' , Rule::in($bool_range)] ,
            'status'        => ['required' , 'integer' , Rule::in($status_range)] ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , $validator->errors());
        }
        $video = VideoModel::find($id);
        if (empty($video)) {
            return self::error('视频记录不存在' , '' , 404);
        }
        /**
         * 处理状态
         * -1-处理失败
         * 0-待处理
         * 1-处理中
         * 2-转码中
         * 3-处理完成
         */
        if (!in_array($video->video_process_status , [-1 , 3])) {
            return self::error('当前状态禁止操作，视频处理状态仅支持【处理完成|处理失败】允许更改状态', '' , 403);
        }
        if (!in_array($video->file_process_status , [-1 , 0 , 2])) {
            return self::error('当前状态禁止操作，文件处理状态仅支持【待处理|处理失败|处理完成】允许更改状态' , '' , 403);
        }
        $video = VideoHandler::handle($video);
        VideoHandler::videoProject($video);
        if ($video->type === 'pro' && empty($video->video_project)) {
            return self::error('源视频所属的视频专题不存在' , '' , 404);
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在');
        }
        $video_project = null;
        if ($param['type'] === 'pro') {
            // 专题
            $video_project = VideoProjectModel::find($param['video_project_id']);
            if (empty($video_project)) {
                return self::error('专题不存在');
            }
            if (VideoModel::findByExcludeIdAndVideoProjectIdAndIndex($video->id , $video_project->id , $param['index'])) {
                return self::error('索引已经存在');
            }
            $param['category_id'] = 0;
        } else {
            // 杂项
            $category = CategoryModel::find($param['category_id']);
            if (empty($category)) {
                return self::error('分类不存在');
            }
            $param['video_project_id']  = 0;
            $param['index']             = 0;
        }
        $user = UserModel::find($param['user_id']);
        if (empty($user)) {
            return self::error('用户不存在');
        }
        if ($param['status'] !== '' && $param['status'] == -1 && $param['fail_reason'] === '') {
            return self::error('请提供失败原因');
        }
        $datetime               = current_datetime();
        $param['weight']        = $param['weight'] === '' ? $video->weight : $param['weight'];
        $param['view_count']    = $param['view_count'] === '' ? 0 : $param['view_count'];
        $param['play_count']    = $param['play_count'] === '' ? 0 : $param['play_count'];
        $param['against_count'] = $param['against_count'] === '' ? 0 : $param['against_count'];
        $param['praise_count']  = $param['praise_count'] === '' ? 0 : $param['praise_count'];
        $param['file_process_status'] = 0;
        $param['updated_at']    = $datetime;
        $video_subtitles        = $param['video_subtitles'] === '' ? [] : json_decode($param['video_subtitles'] , true);
        $is_video_need_handle   = false;
        try {
            DB::beginTransaction();
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
            $video_subtitles = VideoSubtitleModel::getByVideoId($video->id);
            if (
                $video->src !== $param['src'] ||
                ($param['merge_video_subtitle'] == 1 && $video_subtitles->isNotEmpty())
            ) {
                // 视频源发生变化 或者 需要合成字幕
                // 都需要对视频进行重新处理
                $param['video_process_status']  = 0;
                $is_video_need_handle           = true;
            }
            VideoModel::updateById($video->id , array_unit($param , [
                'name' ,
                'user_id' ,
                'module_id' ,
                'category_id' ,
                'src' ,
                'type' ,
                'video_project_id' ,
                'thumb' ,
                'description' ,
                'weight' ,
                'index' ,
                'view_count' ,
                'play_count' ,
                'praise_count' ,
                'against_count' ,
                'merge_video_subtitle' ,
                'status' ,
                'fail_reason' ,
                'video_process_status' ,
                'file_process_status' ,
                'updated_at' ,
            ]));
            ResourceUtil::used($param['src']);
            ResourceUtil::used($param['thumb']);
            if ($video->thumb !== $param['thumb']) {
                ResourceUtil::delete($video->thumb);
            }
            if ($video->src !== $param['src'] ) {
                // 视频源发生变动
                ResourceUtil::delete($video->src);
            }
            DB::commit();
            if ($is_video_need_handle) {
                // 视频处理
                VideoHandleJob::withChain([
                    new VideoResourceHandleJob($video->id) ,
                ])->dispatch($video->id);
            } else {
                VideoResourceHandleJob::dispatch($video->id);
            }
            return self::success('操作成功');
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
            'user_id'               => 'required|integer' ,
            'module_id'             => 'required|integer' ,
            'category_id'           => 'sometimes|integer' ,
            'video_project_id'      => 'sometimes|integer' ,
            'type'                  => ['required' , Rule::in($type_range)] ,
            'weight'                => 'sometimes|integer' ,
            'view_count'            => 'sometimes|integer' ,
            'praise_count'          => 'sometimes|integer' ,
            'against_count'         => 'sometimes|integer' ,
            'play_count'            => 'sometimes|integer' ,
            'index'                 => 'sometimes|integer' ,
            'status'                => ['required' , 'integer' , Rule::in($status_range)] ,
            'merge_video_subtitle'  => ['required' , 'integer' , Rule::in($bool_range)] ,
        ]);
        if ($validator->fails()) {
            return self::error($validator->errors()->first() , $validator->errors());
        }
        $module = ModuleModel::find($param['module_id']);
        if (empty($module)) {
            return self::error('模块不存在');
        }
        $video_project = null;
        if ($param['type'] === 'pro') {
            // 专题
            $video_project = VideoProjectModel::find($param['video_project_id']);
            if (empty($video_project)) {
                return self::error('专题不存在');
            }
            if (VideoModel::findByVideoProjectIdAndIndex($video_project->id , $param['index'])) {
                return self::error('索引已经存在');
            }
            $param['category_id'] = 0;
        } else {
            // 杂项
            $category = CategoryModel::find($param['category_id']);
            if (empty($category)) {
                return self::error('分类不存在');
            }
            $param['video_project_id']  = 0;
            $param['index']             = 0;
        }
        $user = UserModel::find($param['user_id']);
        if (empty($user)) {
            return self::error('用户不存在');
        }
        if ($param['status'] !== '' && $param['status'] == -1 && $param['fail_reason'] === '') {
            return self::error('请提供失败原因');
        }
        $datetime               = date('Y-m-d H:i:s');
        $param['weight']        = $param['weight'] === '' ? 0 : $param['weight'];
        $param['view_count']    = $param['view_count'] === '' ? 0 : $param['view_count'];
        $param['play_count']    = $param['play_count'] === '' ? 0 : $param['play_count'];
        $param['against_count'] = $param['against_count'] === '' ? 0 : $param['against_count'];
        $param['praise_count']  = $param['praise_count'] === '' ? 0 : $param['praise_count'];
        $param['updated_at']    = $datetime;
        $param['created_at']    = $param['created_at'] === '' ? $datetime : $param['created_at'];
        $video_subtitles        = $param['video_subtitles'] === '' ? [] : json_decode($param['video_subtitles'] , true);
        $param['video_process_status']  = 0;
        $param['file_process_status']   = 2;
        try {
            DB::beginTransaction();
            $id = VideoModel::insertGetId(array_unit($param , [
                'name' ,
                'user_id' ,
                'module_id' ,
                'category_id' ,
                'src' ,
                'type' ,
                'video_project_id' ,
                'thumb' ,
                'description' ,
                'weight' ,
                'index' ,
                'view_count' ,
                'play_count' ,
                'praise_count' ,
                'against_count' ,
                'status' ,
                'merge_video_subtitle' ,
                'fail_reason' ,
                'video_process_status' ,
                'file_process_status' ,
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
                    'updated_at'   => $datetime ,
                    'created_at'   => $datetime ,
                ]);
                ResourceUtil::used($v['src']);
            }
            DB::commit();
            VideoHandleJob::dispatch($id);
            return self::success('操作成功' , $id);
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

        // 附加：模块
        VideoHandler::module($res);
        // 附加：用户
        VideoHandler::user($res);
        // 附加：分类
        VideoHandler::category($res);
        // 附加：视频专题
        VideoHandler::videoProject($res);
        // 附加：视频
        VideoHandler::videos($res);
        // 附加：视频字幕
        VideoHandler::videoSubtitles($res);

        return self::success('' , $res);
    }

    public static function destroy(Base $context , $id , array $param = []): array
    {
        try {
            DB::beginTransaction();
            VideoUtil::delete($id);
            DB::commit();
            return self::success('操作成功');
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
            return self::success('操作成功');
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
            return self::success('操作成功');
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
            $video = VideoHandler::handle($video);
            if ($video->video_process_status !== -1) {
                return self::error('包含无效处理状态视频' , '' , 403);
            }
            VideoHandler::videoProject($video);
            if ($video->type === 'pro' && empty($video->video_project)) {
                return self::error('包含类型为专题视频但视频专题不存在的记录');
            }
            $videos[] = $video;
        }
        /**
         * 建立图片目录
         * 移动图片到指定的目录
         */
        $disk   = my_config('app.disk');
        $prefix = FileUtil::prefix();
        foreach ($videos as $video)
        {
            if ($disk !== 'local') {
                // todo 其他存储介质的视频处理
                continue ;
            }
            if ($video->type === 'pro') {
                $save_dir       = FileUtil::generateRealPathByRelativePathWithoutPrefix($video->video_project->name);
            } else {
                $dirname        = my_config('app.dir')['video'];
                $date_string    = date('Ymd' , strtotime($video->video_project->created_at));
                $save_dir       = FileUtil::generateRealPathByRelativePathWithoutPrefix($dirname . '/' . $date_string);
            }
            if (!File::exists($save_dir)) {
                File::mkdir($save_dir , 0777 , true);
            }
            VideoModel::updateById($video->id , [
                'video_process_status' => 0 ,
            ]);
            VideoHandleJob::dispatch($video->id , $prefix , $save_dir);
        }
        return self::success('操作成功');
    }
}
