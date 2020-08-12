<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateVideoSrcTable extends Migration
{
    private $table = 'xq_video_src';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table , function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('video_id')->default(0)->comment('xq_video.id');
            $table->string('src' , 500)->default('')->comment('视频源');
            $table->unsignedInteger('duration')->default(0)->comment('时长');
            $table->unsignedInteger('width')->default(0)->comment('宽');
            $table->unsignedInteger('height')->default(0)->comment('高');
            $table->string('display_aspect_ratio' , 50)->default('')->comment('长宽比，比如：16:9');
            $table->unsignedBigInteger('size')->default(0)->comment('大小，单位：Byte');
            $table->string('definition' , 255)->default('')->comment('清晰度: 360P|480P|720P|1080P|2K|4K ... 等');
            $table->dateTime('create_time')->nullable(true);
        });
        DB::statement("alter table {$this->table} comment '视频源（不同清晰度）'");
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
