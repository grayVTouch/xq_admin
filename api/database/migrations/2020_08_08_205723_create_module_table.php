<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateModuleTable extends Migration
{
    public $table = 'xq_module';

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
            $table->tinyInteger('enable')->default(1)->comment('启用？0-否 1-是');
            $table->tinyInteger('auth')->default(0)->comment('认证？0-否 1-是');
            $table->string('auth_password')->default('')->comment('认证密码？当 auth=1 时，要求提供认证密码');
            $table->integer('weight')->default(0)->comment('权重');
            $table->integer('default')->default(0)->comment('默认？0-否 1-是 记录中仅能有一个是默认的');
            $table->datetime('create_time')->nullable(true);
        });
        DB::statement("alter table {$this->table} comment '模块表'");
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
