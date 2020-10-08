<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateVideoCommentImageTable extends Migration
{
    private $table = 'xq_video_comment_image';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table , function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('video_id')->default(0)->comment('xq_video.id');
            $table->unsignedBigInteger('video_project_id')->default(0)->comment('缓存字段，xq_video.video_project_id');
            $table->unsignedBigInteger('video_comment_id')->default(0)->comment('xq_video_comment.id');
            $table->string('path' , 500)->default('')->comment('路径');

            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
        DB::statement("alter table {$this->table} comment '视频评论-图片'");
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
