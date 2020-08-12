<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateVideoSubtitleTable extends Migration
{
    private $table = 'xq_video_subtitle';
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
            $table->string('name' , 255)->default('')->comment('名称');
            $table->string('src' , 500)->default('')->comment('字幕源');
            $table->dateTime('create_time')->nullable(true);
        });
        DB::statement("alter table {$this->table} comment '视频字幕-仅支持 .vtt 格式'");
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
