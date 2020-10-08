<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAdminPermissionTable extends Migration
{
    public $table = 'xq_admin_permission';

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
            $table->string('value' , 255)->default('')->comment('权限值');
            $table->string('description' , 500)->default('')->comment('描述');
            $table->tinyInteger('is_enabled')->default(1)->comment('是否启用：0-否 1-是');
            $table->unsignedBigInteger('p_id')->default(0)->comment('xq_admin_permission.id');
            $table->integer('weight')->default(0)->comment('权重');

            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
        DB::statement("alter table {$this->table} comment '后台用户-权限表'");
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
