<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAdminTable extends Migration
{
    public $table = 'xq_admin';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table , function (Blueprint $table) {
            $table->id();
            $table->string('username' , 255)->default('')->comment('用户名');
            $table->string('password' , 255)->default('')->comment('密码');
            $table->string('sex' , 100)->default('secret')->comment('性别: male-男 female-女 secret-保密 both-两性 shemale-人妖');
            $table->date('birthday')->nullable(true)->comment('生日');
            $table->string('avatar' , 500)->default('')->comment('头像');
            $table->datetime('last_time')->nullable(true)->comment('最近登陆时间');
            $table->string('last_ip' , 100)->default('')->comment('最近登录ip');
            $table->string('phone' , 50)->default('')->comment('手机');
            $table->string('email' , 50)->default('')->comment('电子邮件');
            $table->unsignedBigInteger('role_id')->default(0)->comment('xq_role.id');
            $table->tinyInteger('is_root')->default(0)->comment('是否超级管理员：0-否 1-是');
            $table->datetime('update_time')->nullable(true);
            $table->datetime('create_time')->nullable(true);
            $table->index('username');
            $table->index('phone');
            $table->index('email');
        });
        DB::statement("alter table {$this->table} comment '后台用户表'");
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
