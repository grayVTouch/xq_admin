<?php

namespace App\Customize\api\admin\job;

use App\Customize\api\admin\job\middleware\BootMiddleware;
use App\Customize\api\admin\model\DiskModel;
use App\Customize\api\admin\model\ImageModel;
use App\Customize\api\admin\model\ImageSubjectModel;
use App\Customize\api\admin\model\ResourceModel;
use App\Customize\api\admin\model\VideoModel;
use App\Customize\api\admin\model\VideoSrcModel;
use App\Customize\api\admin\util\ResourceUtil;
use Core\Lib\File;
use Core\Lib\Throwable;
use Core\Wrapper\FFmpeg;
use Core\Wrapper\FFprobe;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Customize\api\admin\util\FileUtil;
use Illuminate\Support\Facades\DB;
use function api\admin\my_config;
use function api\admin\res_realpath;
use function core\format_path;
use function core\get_filename;
use function core\random;

class ImageSubjectHandleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $imageSubjectId = 0;

    private $prefix         = '';

    /**
     * 图片保存目录（必须由外部提供，防止因为默认存储目录更改导致文件割裂）
     * @var string
     */
    private $saveDir        = '';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $image_subject_id , string $prefix , string $save_dir)
    {
        $this->imageSubjectId = $image_subject_id;
        $this->saveDir = rtrim($save_dir , '/');
        $this->prefix = $prefix;
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
        $image_subject = ImageSubjectModel::find($this->imageSubjectId);
        if (empty($image_subject)) {
            throw new Exception('图片专题不存在【' . $this->imageSubjectId . '】');
        }
        $images = ImageModel::getByImageSubjectId($this->imageSubjectId);
        $disk = DiskModel::findByPrefix($this->prefix);
        if (empty($disk)) {
            throw new Exception('未找到当前 prefix【' . $this->prefix . '】对应的磁盘存储记录');
        }
        $res_url = my_config('app.res_url');
        $res_url = ltrim($res_url , '/');
        foreach ($images as $v)
        {
            try {
                DB::beginTransaction();
                $resource = ResourceModel::findByUrl($v->src);
                if (empty($resource)) {
                    DB::rollBack();
                    continue ;
                }
                $filename   = get_filename($v->src);
                $source     = $resource->path;
                $target     = $this->saveDir . '/' . $filename;
                $target     = format_path($target);
                if (File::exists($target)) {
                    // 文件已经存在，跳过
                    DB::rollBack();
                    continue ;
                }
                $relative_path_without_prefix   = str_replace($disk->path , '' , $target);
                $relative_path_with_prefix      = $disk->prefix . '/' . ltrim($relative_path_without_prefix , '/');
                $url = $res_url . '/' . $relative_path_with_prefix;
                // 移动文件
                File::move($source , $target);
                ImageModel::updateById($v->id , [
                    'src' => $url
                ]);
                // 删除源文件
                ResourceUtil::delete($v->src);
                ResourceUtil::create($url , $target , 'local' , 0 , 0);
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
            }
        }
    }
}
