<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateImageSubjectCommentImageTable extends Migration
{
    public $table = 'xq_image_subject_comment_image';

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
            $table->unsignedBigInteger('image_subject_comment_id')->default(0)->comment('xq_image_subject_comment.id');
            $table->string('path' , 500)->default('')->comment('路径');
            $table->datetime('create_time')->nullable(true);
        });
        DB::statement("alter table {$this->table} comment '图片专题评论-图片表'");
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
