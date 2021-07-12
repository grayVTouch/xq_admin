<?php

namespace App\Customize\api\admin\job;

use App\Customize\api\admin\handler\ImageProjectHandler;
use App\Customize\api\admin\job\middleware\BootMiddleware;
use App\Customize\api\admin\model\ImageModel;
use App\Customize\api\admin\model\ImageProjectModel;
use App\Customize\api\admin\model\ResourceModel;
use App\Customize\api\admin\util\FileUtil;
use App\Customize\api\admin\repository\ResourceRepository;
use Core\Lib\File;
use Core\Lib\ImageProcessor;
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
            if ($image_project->type === 'pro') {
                ResourceRepository::create('' , $save_dir , 'local' , 1 , 0);
                ImageProjectModel::updateById($image_project->id , [
                    'directory' => $save_dir ,
                ]);
            }
            $origin_dir = $save_dir . '/原图';
            $preview_dir = $save_dir . '/预览图';
            if (!File::exists($origin_dir)) {
                File::mkdir($origin_dir , 0777 , true);
            }
            if (!File::exists($preview_dir)) {
                File::mkdir($preview_dir , 0777 , true);
            }
            ImageProjectHandler::images($image_project);
            $index = 0;

            $image_processor = new ImageProcessor($save_dir);
            foreach ($image_project->images as $v)
            {
                $index++;
                try {
                    DB::beginTransaction();
                    /**
                     * *******************
                     * 移动原图
                     * *******************
                     */
                    $resource = ResourceModel::findByUrlOrPath($v->original_src);
                    if (empty($resource)) {
                        DB::rollBack();
                        continue ;
                    }
                    if ($resource->disk !== 'local') {
                        // 跳过非本地存储的资源
                        DB::rollBack();
                        continue ;
                    }
                    $extension      = get_extension($v->original_src);
                    $filename       = "{$image_project->name}【{$index}】.{$extension}";
                    $source_file    = $resource->path;
                    $target_file    = $this->generateRealPath($origin_dir , $filename);
                    $target_url     = FileUtil::generateUrlByRealPath($target_file);
                    if ($source_file !== $target_file) {
                        if (File::exists($target_file)) {
                            // 文件已经存在，删除
                            File::dFile($target_file);
                        }
                        // 移动文件
                        File::move($source_file , $target_file);
                        // 删除源文件
                        ResourceRepository::delete($v->original_src);
                        ResourceRepository::create($target_url , $target_file , 'local' , 1 , 0);
                    }

                    // 更新原图地址
                    $original_file = $target_file;
                    $original_src  = $target_url;

                    /**
                     * *******************
                     * 移动预览图
                     * *******************
                     */
                    $extension = 'webp';
                    if (empty($v->src)) {
                        // 生成预览图
                        $source_file = $image_processor->compress($original_file , [
                            'mode'      => 'fix-width' ,
                            // 质量
                            'quality'     => 75 ,
                            // 处理后图片宽度
                            'width'     => 1280 ,
                            // 输出文件类型（如果指定，那么将会以这种类型输出，否则以源文件类型输出）
                            'extension' => $extension ,
                        ] , false);
                    } else {
                        $resource = ResourceModel::findByUrlOrPath($v->src);
                        if (empty($resource)) {
                            DB::rollBack();
                            continue ;
                        }
                        if ($resource->disk !== 'local') {
                            // 跳过非本地存储的资源
                            DB::rollBack();
                            continue ;
                        }
                        $source_file = $resource->path;
                    }
                    $filename       = "{$image_project->name}【{$index}】【预览图】.{$extension}";
                    $target_file    = $this->generateRealPath($preview_dir , $filename);
                    $target_url     = FileUtil::generateUrlByRealPath($target_file);
                    if ($source_file !== $target_file) {
                        if (File::exists($target_file)) {
                            // 文件已经存在，删除
                            File::dFile($target_file);
                        }
                        File::move($source_file , $target_file);
                        // 删除旧文件
                        ResourceRepository::delete($v->src);
                        ResourceRepository::create($target_url , $target_file , 'local' , 1 , 0);
                    }

                    // 更新预览图地址
                    $src = $target_url;

                    ImageModel::updateById($v->id , [
                        'original_src'  => $original_src ,
                        'src'           => $src ,
                    ]);
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
