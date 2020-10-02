<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateNavTable extends Migration
{
    private $table = 'xq_nav';

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
            $table->string('type' , 255)->default('')->comment('导航菜单类型：目前支持的有： image_subject-图片专题 video_subject-视频专题 等');
            $table->string('value' , 255)->default('')->comment('值');
            $table->string('description' , 500)->default('')->comment('描述');
            $table->unsignedBigInteger('p_id')->default(0)->comment('xq_nav.id');
            $table->tinyInteger('is_enabled')->default(0)->comment('启用？0-否 1-是');
            $table->integer('weight')->default(0)->comment('权重');
            $table->unsignedBigInteger('module_id')->default(0)->comment('xq_module.id');

            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
        DB::statement("alter table {$this->table} comment '菜单表（支持动态新增的菜单）'");
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
