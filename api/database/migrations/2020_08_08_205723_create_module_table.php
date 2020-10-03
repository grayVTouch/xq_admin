<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateModuleTable extends Migration
{
    public $table = 'xq_module';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table , function (Blueprint $table) {
            $table->id();
            $table->string('name' , 255)->default('')->comment('名称');
            $table->string('description' , 500)->default('')->comment('描述');
            $table->tinyInteger('is_enabled')->default(1)->comment('启用？0-否 1-是');
            $table->tinyInteger('is_auth')->default(0)->comment('认证？0-否 1-是');
            $table->integer('is_default')->default(0)->comment('默认？0-否 1-是 记录中仅能有一个是默认的');
            $table->integer('weight')->default(0)->comment('权重');

            $table->timestamps();

            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

        });
        DB::statement("alter table {$this->table} comment '模块表'");
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
