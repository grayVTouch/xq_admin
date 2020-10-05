<?php

namespace App\Customize\api\admin\job;

use App\Customize\api\admin\handler\VideoHandler;
use App\Customize\api\admin\job\middleware\BootMiddleware;
use App\Customize\api\admin\model\DiskModel;
use App\Customize\api\admin\model\ImageModel;
use App\Customize\api\admin\model\ResourceModel;
use App\Customize\api\admin\model\VideoModel;
use App\Customize\api\admin\model\VideoSrcModel;
use App\Customize\api\admin\model\VideoSubtitleModel;
use App\Customize\api\admin\util\ResourceUtil;
use Core\Lib\File;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use function api\admin\my_config;
use function core\format_path;
use function core\get_filename;

class VideoResourceHandleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $videoId        = 0;

    private $prefix         = '';

    /**
     * 图片保存目录（必须由外部提供，防止因为默认存储目录更改导致文件割裂）
     * @var string
     */
    private $saveDir        = '';

    /**
     * @var DiskModel
     */
    private $disk;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $video_id , string $prefix , string $save_dir)
    {
        $this->videoId  = $video_id;
        $this->saveDir  = rtrim($save_dir , '/');
        $this->prefix   = $prefix;
        $disk = DiskModel::findByPrefix($this->prefix);
        if (empty($disk)) {
            throw new Exception('未找到当前 prefix【' . $this->prefix . '】对应的磁盘存储记录');
        }
        $this->disk = $disk;
        if (!File::exists($this->saveDir)) {
            File::mkdir($this->saveDir , 0755 , true);
        }
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
        if ($disk !== 'local') {
            // 不是本地存储，跳过
            return ;
        }
        $video = VideoModel::find($this->videoId);
        if (empty($video)) {
            throw new Exception('视频不存在【' . $this->videoId . '】');
        }
        $video = VideoHandler::handle($video , [
            'videos' ,
            'video_subtitles' ,
        ]);

        /**
         * 移动视频封面等到给定目录
         */
        try {
            DB::beginTransaction();
            $video_resource             = ResourceModel::findByUrl($video->src);
            $thumb_resource             = ResourceModel::findByUrl($video->thumb);
            $thumb_for_program_resource = ResourceModel::findByUrl($video->thumb_for_program);
            $simple_preview_resource    = ResourceModel::findByUrl($video->simple_preview);
            $preview_resource           = ResourceModel::findByUrl($video->preview);

            if (!empty($video_resource) && File::exists($video_resource->path)) {
                $filename       = get_filename($video_resource->path);
                $target_file    = $this->generateRealPath($this->saveDir , $filename);
                if (!File::exists($target_file)) {
                    $target_url     = $this->generateUrlByRealPath($target_file);
                    File::move($video_resource->path , $target_file);
                    VideoModel::updateById($video->id , [
                        'src' => $target_url
                    ]);
                    ResourceUtil::delete($video_resource->url);
                    ResourceUtil::create($target_url , $target_file , 'local' , 1 , 0);
                }
            }
            if (!empty($thumb_for_program_resource) && File::exists($thumb_for_program_resource->path)) {
                $filename       = get_filename($thumb_for_program_resource->path);
                $target_file    = $this->generateRealPath($this->saveDir , $filename);
                if (!File::exists($target_file)) {
                    $target_url     = $this->generateUrlByRealPath($target_file);
                    File::move($thumb_for_program_resource->path , $target_file);
                    VideoModel::updateById($video->id , [
                        'thumb_for_program' => $target_url
                    ]);
                    ResourceUtil::delete($thumb_for_program_resource->url);
                    ResourceUtil::create($target_url , $target_file , 'local' , 1 , 0);
                }
            }
            if (!empty($thumb_resource) && File::exists($thumb_resource->path)) {
                $filename       = get_filename($thumb_resource->path);
                $target_file    = $this->generateRealPath($this->saveDir , $filename);
                if (!File::exists($target_file)) {
                    $target_url     = $this->generateUrlByRealPath($target_file);
                    File::move($thumb_resource->path , $target_file);
                    VideoModel::updateById($video->id , [
                        'thumb' => $target_url
                    ]);
                    ResourceUtil::delete($thumb_resource->url);
                    ResourceUtil::create($target_url , $target_file , 'local' , 1 , 0);
                }
            }
            if (!empty($simple_preview_resource) && File::exists($simple_preview_resource->path)) {
                $filename       = get_filename($simple_preview_resource->path);
                $target_file    = $this->generateRealPath($this->saveDir , $filename);
                if (!File::exists($target_file)) {
                    $target_url     = $this->generateUrlByRealPath($target_file);
                    File::move($simple_preview_resource->path , $target_file);
                    VideoModel::updateById($video->id , [
                        'simple_preview' => $target_url
                    ]);
                    ResourceUtil::delete($simple_preview_resource->url);
                    ResourceUtil::create($target_url , $target_file , 'local' , 1 , 0);
                }
            }
            if (!empty($preview_resource) && File::exists($preview_resource->path)) {
                $filename       = get_filename($preview_resource->path);
                $target_file    = $this->generateRealPath($this->saveDir , $filename);
                if (!File::exists($target_file)) {
                    $target_url     = $this->generateUrlByRealPath($target_file);
                    File::move($preview_resource->path , $target_file);
                    VideoModel::updateById($video->id , [
                        'preview' => $target_url
                    ]);
                    ResourceUtil::delete($preview_resource->url);
                    ResourceUtil::create($target_url , $target_file , 'local' , 1 , 0);
                }
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

        /**
         * 移动视频到给定目录
         */
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
                if (!File::exists($resource->path)) {
                    DB::rollBack();
                    continue ;
                }
                $filename       = get_filename($video->src);
                $source_file    = $resource->path;
                $target_file    = $this->saveDir . '/' . $filename;
                $target_file    = format_path($target_file);
                if (File::exists($target_file)) {
                    // 文件已经存在，跳过
                    DB::rollBack();
                    continue ;
                }
                $target_url = $this->generateUrlByRealPath($target_file);
                // 移动文件
                File::move($source_file , $target_file);
                VideoSrcModel::updateById($v->id , [
                    'src' => $target_url
                ]);
                // 删除源文件
                ResourceUtil::delete($v->src);
                ResourceUtil::create($target_url , $target_file , 'local' , 1 , 0);
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }

        /**
         * 移动字幕文件到给定的目录
         */
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
                if (!File::exists($resource->path)) {
                    DB::rollBack();
                    continue ;
                }
                $filename       = get_filename($video->src);
                $source_file    = $resource->path;
                $target_file    = $this->saveDir . '/' . $filename;
                $target_file    = format_path($target_file);
                if (File::exists($target_file)) {
                    // 文件已经存在，跳过
                    DB::rollBack();
                    continue ;
                }
                $target_url = $this->generateUrlByRealPath($target_file);
                // 移动文件
                File::move($source_file , $target_file);
                VideoSubtitleModel::updateById($v->id , [
                    'src' => $target_url
                ]);
                // 删除源文件
                ResourceUtil::delete($v->src);
                ResourceUtil::create($target_url , $target_file , 'local' , 1 , 0);
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                throw $e;
            }
        }
    }

    private function generateRealPath(string $dir , string $path): string
    {
        return format_path(rtrim($dir , '/') . '/' . ltrim($path , '/'));
    }

    /**
     * 从绝对路径生成相对路径
     *
     * @param  string $path
     * @return string
     */
    private function generateUrlByRealPath(string $real_path = ''): string
    {
        $real_path                      = format_path($real_path);
        $res_url                        = my_config('app.res_url');
        $res_url                        = rtrim($res_url , '/');
        $relative_path_without_prefix   = ltrim(str_replace($this->disk->path , '' , $real_path) , '/');
        $relative_path_with_prefix      = $this->disk->prefix . '/' . $relative_path_without_prefix;
        return $res_url . '/' . $relative_path_with_prefix;
    }
}
