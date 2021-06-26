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
            $table->tinyInteger('is_default')->default(0)->comment('默认？0-否 1-是');
            $table->tinyInteger('is_linked')->default(0)->comment('是否已创建软连接？0-否 1-是');

            $table->timestamps();

            // 这个实际上是 当前 web 项目的快捷方式名称！由于在同一个目录，所以要求不能重复
            $table->unique('prefix');

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
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
