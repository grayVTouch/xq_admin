<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateImageProjectCommentImageTable extends Migration
{
    public $table = 'xq_image_project_comment_image';

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
            $table->unsignedBigInteger('image_project_id')->default(0)->comment('xq_image_project.id');
            $table->unsignedBigInteger('image_project_comment_id')->default(0)->comment('xq_image_project_comment.id');
            $table->string('path' , 500)->default('')->comment('路径');

            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
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
