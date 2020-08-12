<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateImageSubjectTable extends Migration
{
    public $table = 'xq_image_subject';

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
            $table->unsignedBigInteger('user_id')->default(0)->comment('xq_user.id');
            $table->unsignedBigInteger('module_id')->default(0)->comment('xq_module.id');
            $table->unsignedBigInteger('category_id')->default(0)->comment('xq_category.id');
            $table->string('type' , 100)->default('')->comment('类别：pro-专题 misc-杂类');
            $table->unsignedBigInteger('subject_id')->default(0)->comment('xq_subject.id');
            $table->string('thumb' , 500)->default('')->comment('封面');
            $table->string('description' , 500)->default('')->comment('描述');
            $table->integer('weight')->default(0)->comment('权重');
            $table->unsignedBigInteger('view_count')->default(0)->comment('浏览次数');
            $table->unsignedBigInteger('praise_count')->default(0)->comment('获赞次数');
            $table->tinyInteger('status')->default(0)->comment('审核状态：-1-审核失败 0-待审核 1-审核通过');
            $table->string('fail_reason' , 1000)->default('')->comment('失败原因，当 status=-1 时，必须提供');
            $table->datetime('update_time')->nullable(true);
            $table->datetime('create_time')->nullable(true);
            $table->index('module_id');
            $table->index('category_id');
            $table->index('subject_id');
        });
        DB::statement("alter table {$this->table} comment '图片专题表'");
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
