<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResourceTable extends Migration
{
    private $table = 'xq_resource';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table , function (Blueprint $table) {
            $table->id();
            $table->string('path' , 1000)->default('')->comment('资源相对路径');
            $table->tinyInteger('used')->default(0)->comment('已使用？0-否 1-是');
            $table->tinyInteger('is_delete')->default(0)->comment('已删除？0-否 1-是');
            $table->dateTime('create_time')->nullable(true);
        });
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
