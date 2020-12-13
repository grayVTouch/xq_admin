<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateImageAtPositionTable extends Migration
{
    private $table = 'xq_image_at_position';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table , function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('position_id')->default(0)->comment('xq_position.id');
            $table->unsignedBigInteger('module_id')->default(0)->comment('xq_module.id');
            $table->string('platform' , 255)->default('')->comment('缓存字段，xq_position.platform');
            $table->string('src' , 500)->default('')->comment('图片路径');
            $table->string('link' , 500)->default('')->comment('跳转链接');

            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
        DB::statement("alter table {$this->table} comment '定点图片'");
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
