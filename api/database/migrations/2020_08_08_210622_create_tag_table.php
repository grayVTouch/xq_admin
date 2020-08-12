<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTagTable extends Migration
{
    public $table = 'xq_tag';

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
            $table->integer('weight')->default(0)->comment('权重');
            $table->unsignedBigInteger('count')->default(0)->comment('使用次数');
            $table->unsignedBigInteger('module_id')->default(0)->comment('xq_module.id');
            $table->datetime('update_time')->nullable(true);
            $table->datetime('create_time')->nullable(true);
            $table->unique(['name' , 'module_id'] , 'name_module_id');
        });
        DB::statement("alter table {$this->table} comment '标签表'");
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
