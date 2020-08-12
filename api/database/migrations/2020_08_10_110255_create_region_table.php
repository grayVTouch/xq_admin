<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateRegionTable extends Migration
{
    private $table = 'xq_region';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table , function (Blueprint $table) {
            $table->id();
            $table->string('name' , 255)->default('')->comment('地区名称');
            $table->unsignedBigInteger('p_id')->default(0)->comment('xq_region.id');
            $table->string('type' , 50)->default('')->comment('地区类型：country-国家 state-州|邦|省份 region-地区');
            $table->dateTime('create_time')->nullable(true);
            $table->index('p_id');
        });
        DB::statement("alter table {$this->table} comment '全球地区表'");
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
