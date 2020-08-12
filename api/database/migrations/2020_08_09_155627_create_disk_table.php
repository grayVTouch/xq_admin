<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDiskTable extends Migration
{
    private $table = 'xq_disk';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table , function (Blueprint $table) {
            $table->id();
            $table->string('path' , '255')->default('')->comment('本地磁盘的绝对路径，注意正反斜杠！windows 上用 \ ；linux 上用 /');
            $table->string('os' , '255')->default('')->comment('操作系统：windows | linux | mac');
            $table->string('prefix' , '255')->default('')->comment('路径前缀，用于区分不同磁盘，请提供 disk_c 诸如这样的字符串');
            $table->tinyInteger('default')->default(0)->comment('默认？0-否 1-是');
            $table->dateTime('create_time')->nullable(true);
            $table->unique('prefix');
        });
        DB::statement("alter table {$this->table} comment '本地磁盘表'");
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
