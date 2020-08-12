<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateVideoCompanyTable extends Migration
{
    private $table = 'xq_video_company';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->id();
            $table->string('name' , 255)->default('')->comment('名称');
            $table->string('thumb' , 500)->default('')->comment('封面');
            $table->string('description' , 1000)->default('')->comment('描述');
            $table->unsignedBigInteger('country_id')->default(0)->comment('xq_region.id');
            $table->string('country' , 255)->default('')->comment('缓存字段，xq_region.name');
            $table->unsignedBigInteger('module_id')->default(0)->comment('xq_module.id');
            $table->integer('weight')->default(0)->comment('权重');
            $table->dateTime('create_time')->nullable(true);
        });
        DB::statement("alter table {$this->table} comment '视频制作公司表'");
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
