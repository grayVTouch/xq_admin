<?php

namespace App\Customize\api\admin\job;

use App\Customize\api\admin\job\middleware\BootMiddleware;
use App\Customize\api\admin\model\ImageModel;
use App\Customize\api\admin\model\ImageProjectModel;
use App\Customize\api\admin\model\ResourceModel;
use App\Customize\api\admin\util\FileUtil;
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

class ImageProjectResourceHandleJob extends FileBaseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $imageProjectId = 0;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(int $image_project_id)
    {
        $this->imageProjectId = $image_project_id;
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
        ImageProjectModel::updateById($this->imageProjectId , [
            // 处理中
            'process_status' => 1 ,
        ]);
        $disk = my_config('app.disk');
        if ($disk === 'local') {
            // 仅在本地存储模式下才执行移植任务
            $image_project = ImageProjectModel::find($this->imageProjectId);
            if (empty($image_project)) {
                throw new Exception('图片专题不存在【' . $this->imageProjectId . '】');
            }
            $dir_prefix = '';
            if ($image_project->type === 'pro') {
                $dir_prefix = my_config('app.dir')['image_project'];
            } else {
                $dir_prefix = my_config('app.dir')['image'] . '/' . date('Ymd' , strtotime($image_project->created_at));
            }
            // 保存目录
            $save_dir = FileUtil::generateRealPathByRelativePathWithoutPrefix($dir_prefix . '/' . $image_project->name);
            if (!File::exists($save_dir)) {
                File::mkdir($save_dir , 0777 , true);
            }
            $images = ImageModel::getByImageProjectId($this->imageProjectId);
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
                    $filename       = "{$image_project->name}【{$index}】.{$extension}";
                    $source_file    = $resource->path;
                    $target_file    = $this->generateRealPath($save_dir , $filename);
                    if (File::exists($target_file)) {
                        // 文件已经存在，跳过
                        DB::rollBack();
                        continue ;
                    }
                    $target_url = FileUtil::generateUrlByRealPath($target_file);
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
        ImageProjectModel::updateById($this->imageProjectId , [
            // 处理已完成
            'process_status' => 2 ,
        ]);
    }

    public function failed(Exception $e)
    {
        ImageProjectModel::updateById($this->imageProjectId , [
            // 处理失败
            'process_status' => -1 ,
        ]);
    }
}
