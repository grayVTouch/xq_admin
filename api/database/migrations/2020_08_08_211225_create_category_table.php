<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCategoryTable extends Migration
{
    public $table = 'xq_category';

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
            $table->string('type' , 255)->default('')->comment('分类所属的类别: image_subject-图片专题 video_subject-视频专题 video-视频分类 image-图片分类');
            $table->string('description' , 500)->default('')->comment('描述');
            $table->unsignedBigInteger('p_id')->default(0)->comment('xq_category.id');
            $table->tinyInteger('is_enabled')->default(1)->comment('是否启用：0-否 1-是');
            $table->integer('weight')->default(0)->comment('权重');
            $table->unsignedBigInteger('module_id')->default(0)->comment('xq_module.id');

            $table->unsignedBigInteger('user_id')->default(0)->comment('xq_user.id，发布者用户id');
            $table->tinyInteger('status')->default(0)->comment('状态： -1-审核不通过 0-待审核 1-审核通过');
            $table->string('fail_reason' , 255)->default('')->comment('失败原因，当status=-1时，必须提供');

            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
        DB::statement("alter table {$this->table} comment '分类表'");
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
