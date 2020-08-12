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
            $table->string('value' , 255)->default('')->comment('菜单值');
            $table->unsignedBigInteger('p_id')->default(0)->comment('xq_nav.id');
            $table->tinyInteger('is_menu')->default(0)->comment('菜单？0-否 1-是');
            $table->tinyInteger('enable')->default(0)->comment('启用？0-否 1-是');
            $table->integer('weight')->default(0)->comment('权重');
            $table->unsignedBigInteger('module_id')->default(0)->comment('xq_module.id');
            $table->string('platform' , 255)->default('')->comment('平台：app | android | ios | web | mobile');
            $table->datetime('create_time')->nullable(true);
        });
        DB::statement("alter table {$this->table} comment '菜单表-区分不同平台'");
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
