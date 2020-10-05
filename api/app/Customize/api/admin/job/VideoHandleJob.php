<?php

namespace App\Customize\api\admin\job;

use App\Customize\api\admin\handler\VideoHandler;
use App\Customize\api\admin\job\middleware\BootMiddleware;
use App\Customize\api\admin\model\DiskModel;
use App\Customize\api\admin\model\ResourceModel;
use App\Customize\api\admin\model\VideoModel;
use App\Customize\api\admin\model\VideoSrcModel;
use App\Customize\api\admin\model\VideoSubtitleModel;
use App\Customize\api\admin\util\ResourceUtil;
use Core\Lib\File;
use Core\Wrapper\FFmpeg;
use Core\Wrapper\FFprobe;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Customize\api\admin\util\FileUtil;
use function api\admin\my_config;
use function core\format_path;
use function core\random;

class VideoHandleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * 视频源
     *
     * @var int|string
     */
    private $videoId = '';

    /**
     * 临时目录
     *
     * @var string
     */
    private $tempDir = '';

    /**
     * 保存的目录
     *
     * @var string
     */
    private $saveDir = '';

    /**
     * 保存的目录
     *
     * @var string
     */
    private $prefix = '';

    /**
     * 保存的目录
     *
     * @var DiskModel
     */
    private $disk = '';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $video_id , string $prefix , string $save_dir)
    {
        $save_dir       = rtrim($save_dir , '/');
        $this->videoId  = $video_id;
        $this->prefix   = $prefix;
        $this->saveDir  = $save_dir;
        $this->tempDir  = $save_dir . '/video/video_temp_' . $this->videoId;
        $this->disk     = DiskModel::findByPrefix($prefix);

        if (empty($this->disk)) {
            throw new Exception('未找到当前 prefix【' . $this->prefix . '】对应的磁盘存储记录');
        }
    }

    /**
     * 获取任务应该通过的中间件
     *
     * @return array
     */
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
            throw new Exception('未找到 videoId:' . $this->videoId . ' 对应记录');
        }
        $video = VideoHandler::handle($video , [
            'video_subject' ,
            'videos' ,
            'video_subtitles' ,
        ]);
        if ($video->type === 'pro' && empty($video->video_subject)) {
            throw new Exception("视频专题不存在【$video->video_subject_id】");
        }
        // 重置视频处理状态
        VideoModel::updateById($video->id , [
            'process_status' => 0 ,
        ]);

        // 清理旧数据 start -------------------------------------------------------
        if (File::exists($this->tempDir)) {
            File::delete($this->tempDir);
        }
        ResourceUtil::delete($video->thumb_for_program);
        ResourceUtil::delete($video->preview);
        foreach ($video->videos as $v)
        {
            ResourceUtil::delete($v->src);
        }
        foreach ($video->video_subtitles as $v)
        {
            ResourceUtil::delete($v->src);
        }
        VideoSrcModel::delByVideoId($video->id);
        // 清理旧数据 end -------------------------------------------------------

        // ......处理新数据
//        $random                             = random(8 , 'letter' , true);
        $video_save_dir                     = $this->generateRealPath($this->saveDir , $video->type === 'pro' ? $video->video_subject->name : my_config('app.dir')['video'] . '/' . date('Ymd'));
        $merge_video_subtitle               = $video->merge_video_subtitle == 1 && !empty($video->video_subtitles);
        $first_video_subtitle               = $merge_video_subtitle ? $video->video_subtitles[0] : '';
        $first_video_subtitle_resource      = $merge_video_subtitle ? ResourceModel::findByUrl($first_video_subtitle) : null;
        $video_resource                     = ResourceModel::findByUrl($video->src);
        $video_info                         = FFprobe::create($video_resource->path)->coreInfo();

        $video_simple_preview_config        = my_config('app.video_simple_preview');
        $video_preview_config               = my_config('app.video_preview');
        $video_temp_dir                     = $this->tempDir;
        $video_first_frame_duration         = my_config('app.video_frist_frame_duration');
        $video_subtitle_config              = my_config('app.video_subtitle');

        if (!File::exists($video_save_dir)) {
            File::mkdir($video_save_dir, 0755, true);
        }

        if (!File::exists($video_temp_dir)) {
            File::mkdir($video_temp_dir, 0755, true);
        }

        $date       = date('Ymd');
        $datetime   = date('YmdHis');

        /**
         * 视频第一帧
         */
        $video_first_frame_file = $this->generateRealPath($video_save_dir , $this->genMediaSuffix($video->type , $video->name , 'jpeg'));
        $video_first_frame_url  = $this->generateUrlByRealPath($video_first_frame_file);

        FFmpeg::create()
            ->input($video_resource->path)
            ->ss($video_first_frame_duration, 'input')
            ->frames(1)
            ->save($video_first_frame_file);

        ResourceUtil::create($video_first_frame_url , $video_first_frame_file , 'local' , 0 , 0);

        /**
         * 视频简略预览
         */
        $avg_duration           = floor($video_info['duration'] / $video_simple_preview_config['count']);
        $remain_duration        = $video_info['duration'] - $avg_duration * 2;
        $avg_remain_duration    = $remain_duration / $video_simple_preview_config['count'];
        $ts                     = [];
        $input_command          = 'concat:';

        for ($i = 0; $i < $video_simple_preview_config['count']; ++$i)
        {
            $cur_ts         = $video_temp_dir . '/' . $datetime . random(6, 'letter', true) . '.ts';
            $start_duration = $avg_remain_duration + $avg_remain_duration * $i;

            FFmpeg::create()
                ->input($video_resource->path)
                ->ss($start_duration, 'input')
                ->size($video_simple_preview_config['width'], $video_simple_preview_config['height'])
                ->disabledAudio()
                ->duration($video_simple_preview_config['duration'], 'output')
                ->save($cur_ts);

            $input_command .= $cur_ts . '|';
            $ts[] = $cur_ts;
        }

        $input_command                  = rtrim($input_command, '|');
        $video_simple_preview_file      = $this->generateRealPath($video_save_dir , $this->genMediaSuffix($video->type , $video->name , 'mp4'));
        $video_simple_preview_url       = $this->generateUrlByRealPath($video_simple_preview_file);

        FFmpeg::create()
            ->input($input_command)
            ->save($video_simple_preview_file);

        ResourceUtil::create($video_simple_preview_url , $video_simple_preview_file , 'local' , 0 , 0);

        /**
         * 视频完整进度预览
         */
        $previews       = [];
        $preview_count  = floor($video_info['duration'] / $video_preview_config['duration']);
        // 图片合成
        $image_width    = $video_preview_config['count'] * $video_preview_config['width'];
        $image_height   = ceil($preview_count / $video_preview_config['count']) * $video_preview_config['height'];

        // 创建透明的图片
        $cav            = imagecreatetruecolor($image_width , $image_height);
        $transparent    = imagecolorallocatealpha($cav,255,255 , 255 , 127);

        imagecolortransparent($cav , $transparent);
        imagefill($cav,0,0 , $transparent);

        for ($i = 0; $i < $preview_count; ++$i)
        {
            $image      = $video_temp_dir . '/' . $datetime . random(6 , 'letter' , true) . '.jpeg';
            $timepoint  = $i * $video_preview_config['duration'];

            FFmpeg::create()
                ->input($video_resource->path)
                ->ss($timepoint , 'input')
                ->size($video_preview_config['width'] , $video_preview_config['width'] / ($video_info['width'] / $video_info['height']))
                ->frames(1)
                ->save($image);

            $previews[] = $image;
            $image_cav  = imagecreatefromjpeg($image);
            $x          = $i % $video_preview_config['count'] * $video_preview_config['width'];
            $y          = floor($i / $video_preview_config['count']) * $video_preview_config['height'];

            imagecopymerge($cav , $image_cav , $x , $y , 0 , 0 , $video_preview_config['width'] , $video_preview_config['height'] , 100);
        }

        $preview_file   = $this->generateRealPath($video_save_dir , $this->genMediaSuffix($video->type , $video->name ,'jpeg'));
        $preview_url    = $this->generateUrlByRealPath($preview_file);

        imagejpeg($cav , $preview_file);

        ResourceUtil::create($preview_url , $preview_file , 'local' , 0 , 0);

        VideoModel::updateById($video->id , [
            'simple_preview'    => $video_simple_preview_url ,
            'preview'           => $preview_url ,
            'preview_width'     => $video_preview_config['width'] ,
            'preview_height'    => $video_preview_config['height'] ,
            'preview_duration'  => $video_preview_config['duration'] ,
            'preview_line_count' => $video_preview_config['count'] ,
            'preview_count'     => $preview_count ,
            'thumb_for_program' => $video_first_frame_url ,
            'duration'          => $video_info['duration'] ,
            'process_status'    => 1 ,
        ]);

        ResourceUtil::used($video_first_frame_url);
        ResourceUtil::used($preview_url);
        ResourceUtil::used($video_simple_preview_url);

        /**
         * 视频转码
         */
        $video_transcoding_config   = my_config('app.video_transcoding');
        // 用户设置：是否保留原视频（当存在转码视频的时候就会删除原视频，否则该设置项无效）
        $save_origin_video          = my_config('app.save_origin_video');
        // 用于判断是否有必要保留原视频（不受用户设置影响）
        $save_origin                = true;
        // 是否高清视频
        $is_hd                      = false;
        foreach ($video_transcoding_config['specification'] as $k => $v)
        {
            if ($video_info['width'] < $v['w']) {
                continue ;
            }
            if ($v['is_hd']) {
                $is_hd = true;
            }
            $save_origin            = false;
            $transcoded_file        = $this->generateRealPath($video_save_dir , $this->genMediaSuffix($video->type , "{$video->name}【{$k}】" ,'mp4'));
            $transcoded_access_url  = $this->generateUrlByRealPath($transcoded_file);

            $ffmpeg = FFmpeg::create()->input($video_resource->path);
            if ($merge_video_subtitle) {
                $ffmpeg->subtitle($first_video_subtitle_resource->path);
            }
            $ffmpeg->size($v['w'] , $v['h'])
                ->codec($video_transcoding_config['codec'] , 'video')
                ->save($transcoded_file);
            $info = FFprobe::create($transcoded_file)->coreInfo();
            VideoSrcModel::insert([
                'video_id'      => $video->id ,
                'src'           =>  $transcoded_access_url ,
                'duration'      => $info['duration'] ,
                'width'         => $info['width'] ,
                'height'        => $info['height'] ,
                'size'          => $info['size'] ,
                'definition'    => $k ,
                'created_at'   => date('Y-m-d H:i:s') ,
            ]);
            ResourceUtil::create($transcoded_access_url , $transcoded_file , 'local' , 1 , 0);
        }

        if ($is_hd) {
            VideoModel::updateById($video->id , [
                'is_hd' => 1
            ]);
        }

        if ($save_origin_video || $save_origin) {
            $definition             = '原画';
            $transcoded_file        = $this->generateRealPath($video_save_dir , $this->genMediaSuffix($video->type , "{$video->name}【{$definition}】" ,'mp4'));
            $transcoded_access_url  = $this->generateUrlByRealPath($transcoded_file);
            $ffmpeg = FFmpeg::create()->input($video_resource->path);
            if ($merge_video_subtitle) {
                $ffmpeg->subtitle($first_video_subtitle_resource->path);
            }
            $ffmpeg->save($transcoded_file);
            $video_info = FFprobe::create($transcoded_file)->coreInfo();
            VideoSrcModel::insert([
                'video_id'      => $video->id ,
                'src'           => $transcoded_access_url ,
                'duration'      => $video_info['duration'] ,
                'width'         => $video_info['width'] ,
                'height'        => $video_info['height'] ,
                'size'          => $video_info['size'] ,
                'definition'    => $definition ,
                'created_at'   => date('Y-m-d H:i:s') ,
            ]);
            ResourceUtil::used($transcoded_access_url);
            // 删除源文件
            ResourceUtil::delete($video->src);
        } else {
            // 删除原视频文件
            ResourceUtil::delete($video->src);
        }

        if ($merge_video_subtitle) {
            // 字幕合成完毕后删除字幕
            foreach ($video->video_subtitles as $v)
            {
                ResourceUtil::delete($v->src);
            }
            VideoSubtitleModel::delByVideoId($video->id);
        } else {
            // 字幕转换
            foreach ($video->video_subtitles as $v)
            {
                $video_subtitle_resource = ResourceModel::findByUrl($v->src);
                if (!File::exists($video_subtitle_resource->path)) {
                    // 字幕文件不存在，跳过
                    continue ;
                }
                $video_subtitle_convert_file        = $this->generateRealPath($video_save_dir , $this->genMediaSuffix($video->type , "{$video->name}【{$v->name}】" , 'vtt'));
                $video_subtitle_convert_access_url  = $this->generateUrlByRealPath($video_subtitle_convert_file);
                FFmpeg::create()
                    ->input($video_subtitle_resource->path)
                    ->save($video_subtitle_convert_file);
                VideoSubtitleModel::updateById($v->id , [
                    'src' => $video_subtitle_convert_access_url
                ]);
                ResourceUtil::delete($v->src);
                ResourceUtil::create($video_subtitle_convert_access_url , $video_subtitle_convert_file , 'local' , 1 , 0);
            }
        }
        File::delete($video_temp_dir);
        VideoModel::updateById($video->id , [
            'process_status' => 2
        ]);
    }

    // 生成媒体的后缀
    private function genMediaSuffix(string $type , string $name , string $extension): string
    {
        return $type === 'pro' ? $name . $extension : $name . '【' . random(8 , 'letter' , true) . '】' . '.' . $extension;
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

    public function failed(Exception $e)
    {
        VideoModel::updateById($this->videoId , [
            'process_status' => -1 ,
        ]);
        // 删除临时处理目录
        File::delete($this->tempDir);
    }
}
