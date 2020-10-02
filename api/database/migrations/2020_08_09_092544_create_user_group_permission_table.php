<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUserGroupPermissionTable extends Migration
{
    public $table = 'xq_user_group_permission';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table , function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_group_id')->default(0)->comment('xq_user_group.id');
            $table->unsignedBigInteger('user_permission_id')->default(0)->comment('xq_user_permission.id');

            $table->timestamps();

            $table->unique(['user_permission_id' , 'user_group_id'] , 'permission');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
        DB::statement("alter table {$this->table} comment '用户组-用户权限 关联表'");
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
