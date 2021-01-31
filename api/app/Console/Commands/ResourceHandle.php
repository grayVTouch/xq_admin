<?php

namespace App\Console\Commands;

use App\Model\DiskModel;
use App\Model\ResourceModel;
use App\Model\TimerTaskLogModel;
use Illuminate\Console\Command;

class ResourceHandle extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resource:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '从本地磁盘删除无效资源，释放磁盘空间';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    // 单次清理记录数
    public $limit = 1000;

    // 间隔清理时间
    public $interval = 3;

    // 未删除但也未被使用的记录保存多长时间，单位: s
    public $duration = 24 * 3600;

    /**
     * Execute the console command.
     *
     */
    public function handle()
    {
        $start_time = date('Y-m-d H:i:s');
        $timer_task_log = "【start: {$start_time}】 资源清理任务开始执行...";
        $res = ResourceModel::getWaitDeleteByLimitIdAndLimit(0 , $this->limit);
        $time = time();
        while (!$res->isEmpty())
        {
            $last = null;
            foreach ($res as $v)
            {
                $last = $v;
                if ($v->is_deleted == 0) {
                    // 未删除 未使用
                    $created_at = strtotime($v->created_at);
                    // 未被删除
                    if ($created_at + $this->duration > $time) {
                        continue ;
                    }
                }
                $args = explode('/' , $v->path);
                if (!empty($args)) {
                    $prefix = $args[0];
                    $disk = DiskModel::findByPrefix($prefix);
                    if (!empty($disk)) {
                        $real_path = $disk->path . '/' . str_replace($prefix , '' , $v->path);
                        // 删除磁盘中的文件
                        unlink($real_path);
                    }
                }
                ResourceModel::destroy($v->id);
            }
            sleep($this->interval);
            $res = ResourceModel::getWaitDeleteByLimitIdAndLimit($last->id , $this->limit);
        }
        $end_time = date('Y-m-d H:i:s');
        $duration = strtotime($end_time) - strtotime($start_time);
        $timer_task_log .= "删除完毕【end: {$end_time}】，耗费时间：{$duration} s";
        TimerTaskLogModel::log($this->signature , $timer_task_log , date('Y-m-d H:i:s'));
    }
}
