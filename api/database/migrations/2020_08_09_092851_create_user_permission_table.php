<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUserPermissionTable extends Migration
{
    public $table = 'xq_user_permission';
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
            $table->unsignedBigInteger('module_id')->default(0)->comment('xq_module.id');
            $table->datetime('update_time')->nullable(true);
            $table->datetime('create_time')->nullable(true);
        });
        DB::statement("alter table {$this->table} comment '平台用户-权限表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->table );
    }
}
