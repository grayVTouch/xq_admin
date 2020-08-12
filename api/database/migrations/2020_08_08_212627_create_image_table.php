<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateImageTable extends Migration
{
    public $table = 'xq_image';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table , function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('image_subject_id')->default(0)->comment('xq_image_subject.id');
            $table->string('path' , 500)->default('')->comment('路径');
            $table->datetime('create_time')->nullable(true);
        });
        DB::statement("alter table {$this->table} comment '图片专题包含的图片'");
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
