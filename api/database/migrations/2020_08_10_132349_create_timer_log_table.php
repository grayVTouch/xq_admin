<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTimerLogTable extends Migration
{
    private $table = 'xq_timer_task_log';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table , function (Blueprint $table) {
            $table->id();
            $table->string('type' , 255)->default('')->comment('类型');
            $table->longText('log')->comment('内容');
            $table->dateTime('create_time')->nullable(true);
        });
        DB::statement("alter table {$this->table} comment '定时任务日志表'");
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
