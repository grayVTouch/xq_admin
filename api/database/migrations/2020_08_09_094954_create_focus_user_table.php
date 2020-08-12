<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateFocusUserTable extends Migration
{
    private $table = 'xq_focus_user';

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
            $table->unsignedBigInteger('focus_user_id')->default(0)->comment('xq_user.id');
            $table->datetime('create_time')->nullable(true);
            $table->unique(['user_id' , 'focus_user_id']);
        });
        DB::statement("alter table {$this->table} comment '关注的用户'");
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
