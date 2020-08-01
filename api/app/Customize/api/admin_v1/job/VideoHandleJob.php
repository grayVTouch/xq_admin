<?php

namespace App\Customize\api\admin_v1\job;

use App\Customize\api\admin_v1\handler\VideoHandler;
use App\Customize\api\admin_v1\job\middleware\BootMiddleware;
use App\Customize\api\admin_v1\model\VideoModel;
use App\Customize\api\admin_v1\model\VideoSrcModel;
use Core\Lib\File;
use Core\Wrapper\FFmpeg;
use Core\Wrapper\FFprobe;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use function api\admin_v1\my_config;
use function api\admin_v1\res_realpath;
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
        $this->dir = my_config('app.video_temp_dir') . '/video_' . $this->videoId;
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
     */
    public function handle()
    {
        $video = VideoModel::find($this->videoId);
        $video = VideoHandler::handle($video);
        if (empty($video)) {
            throw new Exception('未找到 videoId:' . $this->videoId . ' 对应记录');
        }
        // .......清理旧数据
        Storage::delete($video->thumb_for_program);
        Storage::delete($video->preview);
        foreach ($video->videos as $v)
        {
            Storage::delete($v->src);
        }
        VideoSrcModel::delByVideoId($video->id);

        // ......处理新数据
        $video_src = res_realpath($video->src);
        $video_info = FFprobe::create($video_src)
            ->coreInfo();
        $video_simple_preview = my_config('app.video_simple_preview');
        $video_preview = my_config('app.video_preview');
        $video_temp_dir = $this->dir;
        $video_first_frame_duration = my_config('app.video_frist_frame_duration');
        if (!file_exists($video_temp_dir)) {
            mkdir($video_temp_dir , 0777 , true);
        }
        $date = date('Ymd');
        $datetime = date('YmdHis');

        /**
         * 视频第一帧
         */
        $video_first_frame_filename = $date . '/' . $datetime . random(6 , 'mixed' , true) . '.jpeg';
        $video_first_frame = res_realpath($video_first_frame_filename);
        FFmpeg::create()
            ->input($video_src)
            ->ss($video_first_frame_duration , 'input')
            ->frames(1)
            ->save($video_first_frame);
        /**
         * 视频简略预览
         */
        $avg_duration = floor($video_info['duration'] / $video_simple_preview['count']);
        $remain_duration = $video_info['duration'] - $avg_duration * 2;
        $avg_remain_duration = $remain_duration / $video_simple_preview['count'];
        $ts = [];
        $input_command = 'concat:';
        for ($i = 0; $i < $video_simple_preview['count']; ++$i)
        {
            $cur_ts =  $video_temp_dir . '/' .$datetime . random(6 , 'letter' , true) . '.ts';
            $start_duration = $avg_remain_duration + $avg_remain_duration* $i;
            FFmpeg::create()
                ->input($video_src)
                ->ss($start_duration , 'input')
                ->size($video_simple_preview['width'] , $video_simple_preview['height'])
                ->disabledAudio()
                ->duration($video_simple_preview['duration'] , 'output')
                ->save($cur_ts);
            $input_command .= $cur_ts . '|';
            $ts[] = $cur_ts;
        }
        $input_command = rtrim($input_command , '|');
        $video_simple_preview_filename = $date . '/' . $datetime . random(6 , 'letter' , true) . '.mp4';
        $video_simple_preview = res_realpath($video_simple_preview_filename);
        FFmpeg::create()
            ->input($input_command)
            ->save($video_simple_preview);
        /**
         * 视频完整进度预览
         */
        $previews = [];
        $preview_count = floor($video_info['duration'] / $video_preview['duration']);
        // 图片合成
        $image_width = $video_preview['count'] * $video_preview['width'];
        $image_height = ceil($preview_count / $video_preview['count']) * $video_preview['height'];

        // 创建透明的图片
        $cav = imagecreatetruecolor($image_width , $image_height);
        $transparent = imagecolorallocatealpha($cav,255,255 , 255 , 127);
        imagecolortransparent($cav , $transparent);
        imagefill($cav,0,0 , $transparent);
        for ($i = 0; $i < $preview_count; ++$i)
        {
            $image = $video_temp_dir . '/' . $datetime . random(6 , 'letter' , true) . '.jpg';
            $timepoint = $i * $video_preview['duration'];
            FFmpeg::create()
                ->input($video_src)
                ->ss($timepoint , 'input')
                ->size($video_preview['width'] , $video_preview['width'] / ($video_info['width'] / $video_info['height']))
                ->frames(1)
                ->save($image);
            $previews[] = $image;
            $image_cav = imagecreatefromjpeg($image);
            $x = $i % $video_preview['count'] * $video_preview['width'];
            $y = floor($i / $video_preview['count']) * $video_preview['height'];
            imagecopymerge($cav , $image_cav , $x , $y , 0 , 0 , $video_preview['width'] , $video_preview['height'] , 100);
        }
        $preview_filename = $date . '/' . $datetime . random(6 , 'letter' , true) . '.jpeg';
        $preview = res_realpath($preview_filename);
        imagejpeg($cav , $preview);
        VideoModel::updateById($video->id , [
            'simple_preview'    => $video_simple_preview_filename ,
            'preview'           => $preview_filename ,
            'preview_width'     => $video_preview['width'] ,
            'preview_height'    => $video_preview['height'] ,
            'preview_duration'  => $video_preview['duration'] ,
            'preview_count'     => $preview_count ,
            'thumb_for_program' => $video_first_frame_filename ,
            'duration'          => $video_info['duration'] ,
            'process_status'    => 1 ,
        ]);
        /**
         * 视频转码
         */
        $video_transcoding = my_config('app.video_transcoding');
        $save_origin = true;
        foreach ($video_transcoding['specification'] as $k => $v)
        {
            if ($video_info['width'] < $v['w']) {
                continue ;
            }
            $save_origin = false;
            $filename = $date . '/' . $datetime . random(6 , 'letter' , true) . '.mp4';
            $transcoded_file = res_realpath($filename);
            FFmpeg::create()
                ->input($video_src)
                ->size($v['w'] , $v['h'])
                ->codec($video_transcoding['codec'])
                ->save($transcoded_file);
            $info = FFprobe::create($transcoded_file)->coreInfo();
            VideoSrcModel::insert([
                'video_id' => $video->id ,
                'src' =>  $filename ,
                'duration' => $info['duration'] ,
                'width' => $info['width'] ,
                'height' => $info['height'] ,
                'size' => $info['size'] ,
                'definition' => $k ,
                'create_time' => date('Y-m-d H:i:s') ,
            ]);
        }
        if ($save_origin) {
            VideoSrcModel::insert([
                'video_id' => $video->id ,
                'src' => $video->src ,
                'duration' => $video_info['duration'] ,
                'width' => $video_info['width'] ,
                'height' => $video_info['height'] ,
                'size' => $video_info['size'] ,
                'definition' => 'origin' ,
                'create_time' => date('Y-m-d H:i:s') ,
            ]);
        }
        File::delete($video_temp_dir);
        VideoModel::updateById($video->id , [
            'process_status' => 2
        ]);
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
