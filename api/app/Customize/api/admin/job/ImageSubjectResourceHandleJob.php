<?php

namespace App\Customize\api\admin\job;

use App\Customize\api\admin\job\middleware\BootMiddleware;
use App\Customize\api\admin\model\DiskModel;
use App\Customize\api\admin\model\ImageModel;
use App\Customize\api\admin\model\ImageProjectModel;
use App\Customize\api\admin\model\ResourceModel;
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
use function core\get_extension;

class ImageSubjectResourceHandleJob extends FileBaseJob implements ShouldQueue
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
        $this->disk     = DiskModel::findByPrefix($prefix);

        if (empty($this->disk)) {
            throw new Exception('未找到当前 prefix【' . $this->prefix . '】对应的磁盘存储记录');
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
        $image_subject = ImageProjectModel::find($this->imageSubjectId);
        if (empty($image_subject)) {
            throw new Exception('图片专题不存在【' . $this->imageSubjectId . '】');
        }
        $images = ImageModel::getByImageSubjectId($this->imageSubjectId);

        $index = 0;
        foreach ($images as $v)
        {
            $index++;
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
                $filename       = "{$image_subject->name}【{$index}】.{$extension}";
                $source_file    = $resource->path;
                $target_file    = $this->generateRealPath($this->saveDir , $filename);
                if (File::exists($target_file)) {
                    // 文件已经存在，跳过
                    DB::rollBack();
                    continue ;
                }
                $target_url = $this->generateUrlByRealPath($target_file);
                // 移动文件
                File::move($source_file , $target_file);
                ImageModel::updateById($v->id , [
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
}
