<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateVideoCommentTable extends Migration
{
    private $table = 'xq_video_comment';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->id();
            $table->text('content')->comment('评论内容');
            $table->unsignedBigInteger('p_id')->default(0)->comment('xq_video_comment.id');
            $table->unsignedBigInteger('user_id')->default(0)->comment('xq_user.id');
            $table->unsignedBigInteger('video_id')->default(0)->comment('xq_video.id');
            $table->unsignedBigInteger('video_subject_id')->default(0)->comment('缓存字段，xq_video.video_subject_id');
            $table->unsignedBigInteger('praise_count')->default(0)->comment('获赞次数');
            $table->unsignedBigInteger('against_count')->default(0)->comment('反对次数');
            $table->tinyInteger('status')->default(1)->comment('状态：-1-审核不通过 0-审核中 1-审核通过');

            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
        DB::statement("alter table {$this->table} comment '视频评论'");
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
