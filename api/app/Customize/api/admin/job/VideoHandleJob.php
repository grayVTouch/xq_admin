<?php

namespace App\Customize\api\admin\job;

use App\Customize\api\admin\handler\VideoHandler;
use App\Customize\api\admin\job\middleware\BootMiddleware;
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
use function core\random;

class VideoHandleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // 视频源
    private $videoId = '';

    private $dir = '';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $video_id)
    {
        //
        $this->videoId = $video_id;
        $this->dir = FileUtil::getRealPathByRelativePathWithoutPrefix('video/video_' . $this->videoId);
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
        $video = VideoModel::find($this->videoId);
        if (empty($video)) {
            throw new Exception('未找到 videoId:' . $this->videoId . ' 对应记录');
        }
        $video = VideoHandler::handle($video);
        VideoModel::updateById($video->id , [
            'process_status' => 0 ,
        ]);
        // .......清理旧数据
        if (File::exists($this->dir)) {
            File::delete($this->dir);
        }
        ResourceUtil::delete($video->thumb_for_program);
        ResourceUtil::delete($video->preview);
        foreach ($video->videos as $v) {
            ResourceUtil::delete($v->src);
        }
        VideoSrcModel::delByVideoId($video->id);


        // ......处理新数据
        $merge_video_subtitle       = $video->merge_video_subtitle == 1 && !empty($video->video_subtitles);
        $first_video_subtitle       = $merge_video_subtitle ? $video->video_subtitles[0] : null;
        $first_video_subtitle_file  = $merge_video_subtitle ? FileUtil::getRealPathByRelativePathWithPrefix($first_video_subtitle->src) : '';
        $video_src                  = FileUtil::getRealPathByRelativePathWithPrefix($video->src);
        $video_info                 = FFprobe::create($video_src)->coreInfo();

        $video_simple_preview   = my_config('app.video_simple_preview');
        $video_preview          = my_config('app.video_preview');
        $video_temp_dir         = $this->dir;
        $video_first_frame_duration = my_config('app.video_frist_frame_duration');
        $video_subtitle         = my_config('app.video_subtitle');

        if (!File::exists($video_temp_dir)) {
            File::mkdir($video_temp_dir, 0644, true);
        }

        $date = date('Ymd');
        $datetime = date('YmdHis');

        /**
         * 视频第一帧
         */
        $video_first_frame_filename = FileUtil::generateRelativePathWithPrefix($this->genMediaSuffix('jpeg'));
        $video_first_frame = FileUtil::getRealPathByRelativePathWithPrefix($video_first_frame_filename);

        FFmpeg::create()
            ->input($video_src)
            ->ss($video_first_frame_duration, 'input')
            ->frames(1)
            ->save($video_first_frame);

        ResourceUtil::create($video_first_frame_filename);

        /**
         * 视频简略预览
         */
        $avg_duration = floor($video_info['duration'] / $video_simple_preview['count']);
        $remain_duration = $video_info['duration'] - $avg_duration * 2;
        $avg_remain_duration = $remain_duration / $video_simple_preview['count'];
        $ts = [];
        $input_command = 'concat:';

        for ($i = 0; $i < $video_simple_preview['count']; ++$i) {
            $cur_ts = $video_temp_dir . '/' . $datetime . random(6, 'letter', true) . '.ts';
            $start_duration = $avg_remain_duration + $avg_remain_duration * $i;

            FFmpeg::create()
                ->input($video_src)
                ->ss($start_duration, 'input')
                ->size($video_simple_preview['width'], $video_simple_preview['height'])
                ->disabledAudio()
                ->duration($video_simple_preview['duration'], 'output')
                ->save($cur_ts);

            $input_command .= $cur_ts . '|';
            $ts[] = $cur_ts;
        }

        $input_command = rtrim($input_command, '|');
        $video_simple_preview_filename = FileUtil::generateRelativePathWithPrefix($this->genMediaSuffix('mp4'));
        $video_simple_preview = FileUtil::getRealPathByRelativePathWithPrefix($video_simple_preview_filename);

        FFmpeg::create()
            ->input($input_command)
            ->save($video_simple_preview);

        ResourceUtil::create($video_simple_preview_filename);

        /**
         * 视频完整进度预览
         */
        $previews       = [];
        $preview_count  = floor($video_info['duration'] / $video_preview['duration']);
        // 图片合成
        $image_width    = $video_preview['count'] * $video_preview['width'];
        $image_height   = ceil($preview_count / $video_preview['count']) * $video_preview['height'];

        // 创建透明的图片
        $cav            = imagecreatetruecolor($image_width , $image_height);
        $transparent    = imagecolorallocatealpha($cav,255,255 , 255 , 127);

        imagecolortransparent($cav , $transparent);
        imagefill($cav,0,0 , $transparent);

        for ($i = 0; $i < $preview_count; ++$i)
        {
            $image      = $video_temp_dir . '/' . $datetime . random(6 , 'letter' , true) . '.jpeg';
            $timepoint  = $i * $video_preview['duration'];

            FFmpeg::create()
                ->input($video_src)
                ->ss($timepoint , 'input')
                ->size($video_preview['width'] , $video_preview['width'] / ($video_info['width'] / $video_info['height']))
                ->frames(1)
                ->save($image);

            $previews[] = $image;
            $image_cav  = imagecreatefromjpeg($image);
            $x          = $i % $video_preview['count'] * $video_preview['width'];
            $y          = floor($i / $video_preview['count']) * $video_preview['height'];

            imagecopymerge($cav , $image_cav , $x , $y , 0 , 0 , $video_preview['width'] , $video_preview['height'] , 100);
        }

        $preview_filename   = FileUtil::generateRelativePathWithPrefix($this->genMediaSuffix('jpeg'));
        $preview            = FileUtil::getRealPathByRelativePathWithPrefix($preview_filename);

        imagejpeg($cav , $preview);

        ResourceUtil::create($preview_filename);

        VideoModel::updateById($video->id , [
            'simple_preview'    => $video_simple_preview_filename ,
            'preview'           => $preview_filename ,
            'preview_width'     => $video_preview['width'] ,
            'preview_height'    => $video_preview['height'] ,
            'preview_duration'  => $video_preview['duration'] ,
            'preview_line_count' => $video_preview['count'] ,
            'preview_count'     => $preview_count ,
            'thumb_for_program' => $video_first_frame_filename ,
            'duration'          => $video_info['duration'] ,
            'process_status'    => 1 ,
        ]);

        ResourceUtil::used($video_first_frame_filename);
        ResourceUtil::used($preview_filename);
        ResourceUtil::used($video_simple_preview_filename);

        /**
         * 视频转码
         */
        $video_transcoding  = my_config('app.video_transcoding');
        // 用户设置：是否保留原视频（当存在转码视频的时候就会删除原视频，否则该设置项无效）
        $save_origin_video  = my_config('app.save_origin_video');
        // 用于判断是否有必要保留原视频（不受用户设置影响）
        $save_origin        = true;
        // 是否高清视频
        $is_hd              = false;
        foreach ($video_transcoding['specification'] as $k => $v)
        {
            if ($video_info['width'] < $v['w']) {
                continue ;
            }
            if ($v['is_hd']) {
                $is_hd = true;
            }
            $save_origin        = false;
            $filename           = FileUtil::generateRelativePathWithPrefix($this->genMediaSuffix('mp4'));
            $transcoded_file    = FileUtil::getRealPathByRelativePathWithPrefix($filename);

            $ffmpeg = FFmpeg::create()
                ->input($video_src);
            if ($merge_video_subtitle) {
                $ffmpeg->subtitle($first_video_subtitle_file);
            }
            $ffmpeg->size($v['w'] , $v['h'])
                ->codec($video_transcoding['codec'] , 'video')
                ->save($transcoded_file);
            $info = FFprobe::create($transcoded_file)->coreInfo();
            VideoSrcModel::insert([
                'video_id'      => $video->id ,
                'src'           =>  $filename ,
                'duration'      => $info['duration'] ,
                'width'         => $info['width'] ,
                'height'        => $info['height'] ,
                'size'          => $info['size'] ,
                'definition'    => $k ,
                'created_at'   => date('Y-m-d H:i:s') ,
            ]);
            ResourceUtil::create($filename , 1);
        }

        if ($is_hd) {
            VideoModel::updateById($video->id , [
                'is_hd' => 1
            ]);
        }

        if ($save_origin_video || $save_origin) {
            $filename           = FileUtil::generateRelativePathWithPrefix($this->genMediaSuffix('mp4'));
            $transcoded_file    = FileUtil::getRealPathByRelativePathWithPrefix($filename);
            $ffmpeg = FFmpeg::create()->input($video_src);
            if ($merge_video_subtitle) {
                $ffmpeg->subtitle($first_video_subtitle_file);
            }
            $ffmpeg->save($transcoded_file);
            $video_info = FFprobe::create($transcoded_file)->coreInfo();
            VideoSrcModel::insert([
                'video_id'      => $video->id ,
                'src'           => $filename ,
                'duration'      => $video_info['duration'] ,
                'width'         => $video_info['width'] ,
                'height'        => $video_info['height'] ,
                'size'          => $video_info['size'] ,
                'definition'    => '原画' ,
                'created_at'   => date('Y-m-d H:i:s') ,
            ]);
            ResourceUtil::used($video->src);
        } else {
            // 删除原视频文件
            ResourceUtil::delete($video->src);
        }

        if ($merge_video_subtitle) {
            // 字幕合成完毕后删除字幕 和 源视频
            foreach ($video->video_subtitles as $v)
            {
                ResourceUtil::delete($v->src);
            }
            VideoSubtitleModel::delByVideoId($video->id);
            if ($save_origin_video || $save_origin) {
                ResourceUtil::delete($video->src);
            }
        } else {
            // 字幕转换
            foreach ($video->video_subtitles as $v)
            {
                if (!FileUtil::exists($v->src)) {
                    continue ;
                }
                $video_subtitle_file = FileUtil::getRealPathByRelativePathWithPrefix($v->src);
                $video_subtitle_convert_filename = FileUtil::generateRelativePathWithPrefix($this->genMediaSuffix('vtt'));
                $video_subtitle_convert_file = FileUtil::getRealPathByRelativePathWithPrefix($video_subtitle_convert_filename);
                FFmpeg::create()
                    ->input($video_subtitle_file)
                    ->save($video_subtitle_convert_file);
                VideoSubtitleModel::updateById($v->id , [
                    'src' => $video_subtitle_convert_filename
                ]);
                ResourceUtil::delete($v->src);
                ResourceUtil::create($video_subtitle_convert_filename , 1);
            }
        }

        File::delete($video_temp_dir);

        VideoModel::updateById($video->id , [
            'process_status' => 2
        ]);
    }

    // 生成媒体的后缀
    private function genMediaSuffix(string $extension): string
    {
        $date = date('Ymd');
        $datetime = date('YmdHis');
        return $date . '/' . $datetime . random(6 , 'letter' , true) . '.' . $extension;
    }
    public function failed(Exception $e)
    {
        VideoModel::updateById($this->videoId , [
            'process_status' => -1 ,
        ]);
        // 删除临时处理目录
        File::delete($this->dir);
    }
}
