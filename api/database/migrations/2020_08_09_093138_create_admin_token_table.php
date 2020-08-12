<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAdminTokenTable extends Migration
{
    public $table = 'xq_admin_token';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table , function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default(0)->comment('xq_user.id');
            $table->string('token' , 255)->default('')->comment('token');
            $table->datetime('expired')->nullable(false)->comment('过期时间');
            $table->datetime('create_time')->nullable(true);
            $table->index('user_id');
            $table->unique('token');
        });
        DB::statement("alter table {$this->table} comment '后台用户 token 表'");
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
