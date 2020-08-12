<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateImageSubjectCommentTable extends Migration
{
    public $table = 'xq_image_subject_comment';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table , function (Blueprint $table) {
            $table->id();
            $table->text('content')->comment('内容');
            $table->unsignedBigInteger('image_subject_id')->default(0)->comment('xq_image_subject.id');
            $table->unsignedBigInteger('user_id')->default(0)->comment('xq_user.id');
            $table->unsignedBigInteger('p_id')->default(0)->comment('xq_image_subject_comment.id');
            $table->unsignedBigInteger('praise_count')->default(0)->comment('获赞次数');
            $table->unsignedBigInteger('against_count')->default(0)->comment('反对次数');
            $table->tinyInteger('status')->default(0)->comment('审核状态：-1-审核不通过 0-审核中 1-审核通过');
            $table->datetime('update_time')->nullable(true);
            $table->datetime('create_time')->nullable(true);
        });
        DB::statement("alter table {$this->table} comment '图片专题评论'");
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
