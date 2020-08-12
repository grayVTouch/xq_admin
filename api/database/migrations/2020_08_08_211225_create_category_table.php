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
            $table->string('description' , 500)->default('')->comment('描述');
            $table->unsignedBigInteger('p_id')->default(0)->comment('xq_category.id');
            $table->tinyInteger('enable')->default(1)->comment('是否启用：0-否 1-是');
            $table->integer('weight')->default(0)->comment('权重');
            $table->unsignedBigInteger('module_id')->default(0)->comment('xq_module.id');
            $table->datetime('update_time')->nullable(true);
            $table->datetime('create_time')->nullable(true);
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
