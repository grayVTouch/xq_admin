<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUserGroupTable extends Migration
{
    public $table = 'xq_user_group';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table , function (Blueprint $table) {
            $table->id();
            $table->string('name' , 255)->default('')->comment('组名');
            $table->unsignedBigInteger('p_id')->default(0)->comment('xq_user_group.id');
            $table->unsignedBigInteger('module_id')->default(0)->comment('xq_module.id');
            $table->datetime('update_time')->nullable(true);
            $table->datetime('create_time')->nullable(true);
            $table->unique('name');
        });
        DB::statement("alter table {$this->table} comment '平台用户组表'");
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
