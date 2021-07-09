<?php

namespace App\Customize\api\admin\job;

use App\Customize\api\admin\handler\VideoHandler;
use App\Customize\api\admin\job\middleware\BootMiddleware;
use App\Customize\api\admin\model\ImageModel;
use App\Customize\api\admin\model\VideoModel;
use App\Customize\api\admin\model\ResourceModel;
use App\Customize\api\admin\model\VideoSrcModel;
use App\Customize\api\admin\model\VideoSubtitleModel;
use App\Customize\api\admin\util\FileUtil;
use App\Customize\api\admin\util\ResourceUtil;
use Core\Lib\File;
use Core\Wrapper\FFmpeg;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use function api\admin\my_config;
use function core\get_extension;
use function core\random;

class VideoResourceHandleJob extends FileBaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $videoId = 0;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $video_id)
    {
        $this->videoId = $video_id;
    }

    public function middleware()
    {
        return [
            new BootMiddleware() ,
        ];
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        $disk = my_config('app.disk');
        if ($disk === 'local') {
            // 仅在本地存储模式下才执行移植任务
            $video = VideoModel::find($this->videoId);
            if (empty($video)) {
                throw new Exception('视频专题不存在【' . $this->videoId . '】');
            }
            if ($video->file_process_status !== 0) {
                // 当前文件处理状态无需处理
                return ;
            }
            VideoModel::updateById($this->videoId , [
                // 处理中
                'file_process_status' => 1 ,
            ]);
            $video = VideoHandler::handle($video);
            // 附加：视频专题
            VideoHandler::videoProject($video);
            // 附加：视频
            VideoHandler::videos($video);
            // 附加：字幕
            VideoHandler::videoSubtitles($video);
            $dir_prefix = '';
            $dirname    = '';
            if ($video->type === 'pro') {
                if (empty($video->video_project)) {
                    throw new Exception("视频属于视频专题，但是对应的视频专题【{$video->video_project_id}】记录未找到");
                }
                $dir_prefix = my_config('app.dir')['video_project'];
                $dirname    = $video->video_project->name;
            } else {
                $dir_prefix = my_config('app.dir')['video'] . '/' . date('Ymd' , strtotime($video->created_at));
                $dirname    = $video->name;
            }
            // 保存目录
            $save_dir = FileUtil::generateRealPathByRelativePathWithoutPrefix($dir_prefix . '/' . $dirname);
            if (!File::exists($save_dir)) {
                File::mkdir($save_dir , 0777 , true);
            }
            // 处理文件名称
            $get_video_name = function($type , $name , $index){
                if ($type === 'pro') {
                    // [sprintf 函数可访问右侧链接](https://www.runoob.com/php/func-string-sprintf.html)
                    return empty($name) ? sprintf("%'03s" , $index) : $name;
                }
                return $name;
            };
            $video_name = $get_video_name($video->type , $video->name , $video->index);
            // 第一帧图片 + 预览图片 + 预览视频
            $video_first_frame_file         = $this->generateRealPath($save_dir , $this->generateMediaSuffix($video->type , $video_name . '【第一帧】' , 'jpeg'));
            $video_first_frame_url          = FileUtil::generateUrlByRealPath($video_first_frame_file);
            $video_simple_preview_file      = $this->generateRealPath($save_dir , $this->generateMediaSuffix($video->type , $video_name . '【预览】' , 'mp4'));
            $video_simple_preview_url       = FileUtil::generateUrlByRealPath($video_simple_preview_file);
            $preview_file                   = $this->generateRealPath($save_dir , $this->generateMediaSuffix($video->type , $video_name . '【预览】' ,'jpeg'));
            $preview_url                    = FileUtil::generateUrlByRealPath($preview_file);
            if (!File::exists($video_first_frame_file)) {
                // 移动文件
                $resource = ResourceModel::findByUrl($video->thumb_for_program);
                if (empty($resource)) {
                    throw new Exception("资源记录未找到：{$video->thumb_for_program}");
                }
                File::move($resource->path , $video_first_frame_file);
                VideoModel::updateById($video->id , [
                    'thumb_for_program' => $video_first_frame_url
                ]);
                // 删除源文件
                ResourceUtil::delete($video->thumb_for_program);
                ResourceUtil::create($video_first_frame_url , $video_first_frame_file , 'local' , 1 , 0);
            }
            if (!File::exists($video_simple_preview_file)) {
                // 移动文件
                $resource = ResourceModel::findByUrl($video->simple_preview);
                if (empty($resource)) {
                    throw new Exception("资源记录未找到：{$video->simple_preview}");
                }
                File::move($resource->path , $video_simple_preview_file);
                VideoModel::updateById($video->id , [
                    'simple_preview' => $video_simple_preview_url
                ]);
                // 删除源文件
                ResourceUtil::delete($video->simple_preview);
                ResourceUtil::create($video_simple_preview_url , $video_simple_preview_file , 'local' , 1 , 0);
            }
            if (!File::exists($preview_file)) {
                // 移动文件
                $resource = ResourceModel::findByUrl($video->preview);
                if (empty($resource)) {
                    throw new Exception("资源记录未找到：{$video->preview}");
                }
                File::move($resource->path , $preview_file);
                VideoModel::updateById($video->id , [
                    'preview' => $preview_url
                ]);
                // 删除源文件
                ResourceUtil::delete($video->preview);
                ResourceUtil::create($preview_url , $preview_file , 'local' , 1 , 0);
            }
            // 视频文件
            foreach ($video->videos as $v)
            {
                try {
                    DB::beginTransaction();
                    $resource = ResourceModel::findByUrl($v->src);
                    if (empty($resource)) {
                        DB::rollBack();
                        continue ;
                    }
                    if ($resource->disk !== 'local') {
                        // 跳过非本地存储的资源
                        DB::rollBack();
                        continue ;
                    }
                    $extension      = get_extension($v->src);
                    $filename       = $this->generateVideoMediaSuffix($video->type , $v->definition , $video->index , $video->name , $extension);
                    $source_file    = $resource->path;
                    $target_file    = $this->generateRealPath($save_dir , $filename);
                    if ($source_file !== $target_file) {
                        if (File::exists($target_file)) {
                            // 文件已经存在，删除
                            File::dFile($target_file);
                        }
                        $target_url = FileUtil::generateUrlByRealPath($target_file);
                        // 移动文件
                        File::move($source_file , $target_file);
                        VideoSrcModel::updateById($v->id , [
                            'src' => $target_url
                        ]);
                        // 删除源文件
                        ResourceUtil::delete($v->src);
                        ResourceUtil::create($target_url , $target_file , 'local' , 1 , 0);
                    }
                    DB::commit();
                } catch (Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            }
            // 字幕文件
            foreach ($video->video_subtitles as $v)
            {
                try {
                    DB::beginTransaction();
                    $resource = ResourceModel::findByUrl($v->src);
                    if (empty($resource)) {
                        DB::rollBack();
                        continue ;
                    }
                    if ($resource->disk !== 'local') {
                        // 跳过非本地存储的资源
                        DB::rollBack();
                        continue ;
                    }
                    $extension      = get_extension($v->src);
                    $filename       =  $this->generateMediaSuffix($video->type , "{$video_name}【{$v->name}】" , 'vtt');
                    $source_file    = $resource->path;
                    $target_file    = $this->generateRealPath($save_dir , $filename);
                    if ($source_file !== $target_file) {
                        if (File::exists($target_file)) {
                            // 文件已经存在，删除
                            File::dFile($target_file);
                        }
                        if (!in_array($extension , ['vtt'])) {
                            // 非 web vtt 格式 - 转码 并保存到目标位置
                            FFmpeg::create()
                                ->input($resource->path)
                                ->quiet()
                                ->save($target_file);
                        } else {
                            // 移动文件
                            File::move($source_file , $target_file);
                        }
                        $target_url = FileUtil::generateUrlByRealPath($target_file);
                        VideoSubtitleModel::updateById($v->id , [
                            'src' => $target_url
                        ]);
                        // 删除源文件
                        ResourceUtil::delete($v->src);
                        ResourceUtil::create($target_url , $target_file , 'local' , 1 , 0);
                    }
                    DB::commit();
                } catch (Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            }
        }
        VideoModel::updateById($this->videoId , [
            // 处理已完成
            'file_process_status' => 2 ,
        ]);
    }

    private function generateVideoMediaSuffix(string $type , string $definition , ?int $index , string $name , string $extension): string
    {
        if ($type === 'misc') {
            return $name . '【' . $definition . '】' . '【' . random(8 , 'letter' , true) . '】' . '.' . $extension;
        }
        if ($index < 10) {
            $index = '000' . $index;
        } else if ($index < 100) {
            $index = '00' . $index;
        } else if ($index < 1000) {
            $index = '0' . $index;
        } else {
            // 其他
        }
        return $name . '【' . $definition . '】 ' . $index . '.' . $extension;
    }

    // 生成媒体的后缀
    private function generateMediaSuffix(string $type , string $name , string $extension): string
    {
        return $type === 'pro' ? $name . '.' . $extension : $name . '【' . random(8 , 'letter' , true) . '】' . '.' . $extension;
    }

    public function failed(Exception $e)
    {
        VideoModel::updateById($this->videoId , [
            // 处理失败
            'file_process_status' => -1 ,
        ]);
    }
}
