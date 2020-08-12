<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateVideoSubjectTable extends Migration
{
    private $table = 'xq_video_subject';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table , function (Blueprint $table) {
            $table->id();
            $table->string('name' , 255)->default('')->comment('名称');
            $table->string('thumb' , 500)->default('')->comment('封面');
            $table->decimal('score' , 13 , 2)->default(0)->comment('评分');
            $table->date('release_time')->nullable(true)->comment('发布时间');
            $table->date('end_time')->nullable(true)->comment('完结时间');
            $table->string('status' , 30)->default('completed')->comment('状态：making-制作中 completed-已完结 terminated-已终止（部分完成）');
            $table->unsignedSmallInteger('count')->default(0)->comment('视频数量');
            $table->unsignedBigInteger('play_count')->default(0)->comment('播放数量');
            $table->string('description' , 1000)->default('')->comment('描述');
            $table->unsignedBigInteger('video_series_id')->default(0)->comment('xq_video_series.id');
            $table->unsignedBigInteger('video_company_id')->default(0)->comment('xq_video_company.id');
            $table->unsignedBigInteger('module_id')->default(0)->comment('xq_module.id');
            $table->integer('weight')->default(0)->comment('权重');
            $table->datetime('create_time')->nullable(true);
        });
        DB::statement("alter table {$this->table} comment '视频专题'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}
