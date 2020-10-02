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

            $table->unsignedBigInteger('user_id')->default(0)->comment('xq_user.id，发布者用户id');
            $table->tinyInteger('status')->default(0)->comment('状态： -1-审核不通过 0-待审核 1-审核通过');
            $table->string('fail_reason' , 255)->default('')->comment('失败原因，当status=-1时，必须提供');

            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
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
