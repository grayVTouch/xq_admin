<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateRelationTagTable extends Migration
{
    public $table = 'xq_relation_tag';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table , function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tag_id')->default(0)->comment('xq_tag.id');
            $table->unsignedBigInteger('module_id')->default(0)->comment('xq_module.id');
            $table->string('name' , 255)->default('')->comment('缓存字段，xq_tag.name');
            $table->string('relation_type' , 255)->default('')->comment('关联类型： image_subject-图片专题 video_project-视频专题 ...等');
            $table->unsignedBigInteger('relation_id')->default(0)->comment('对应关联表中的 id');

            $table->timestamps();

            $table->unique(['module_id' , 'relation_type' , 'relation_id' , 'tag_id'] , 'unique');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
        DB::statement("alter table {$this->table} comment '关联标签'");
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
