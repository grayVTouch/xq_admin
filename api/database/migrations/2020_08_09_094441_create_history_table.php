<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateHistoryTable extends Migration
{
    private $table = 'xq_history';

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
            $table->string('relation_type' , 255)->default('')->comment('关联类型: 比如 image_subject-图片专题');
            $table->unsignedBigInteger('relation_id')->default(0)->comment('关联表id');
            $table->unsignedBigInteger('module_id')->default(0)->comment('xq_module.id');
            $table->date('date')->nullable(true)->comment('创建日期');
            $table->time('time')->nullable(true)->comment('创建时间');
            $table->datetime('create_time')->nullable(true);
        });
        DB::statement("alter table {$this->table} comment '活动记录'");
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
