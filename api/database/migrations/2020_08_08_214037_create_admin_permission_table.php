<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAdminPermissionTable extends Migration
{
    public $table = 'xq_admin_permission';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->table , function (Blueprint $table) {
            $table->id();
            $table->string('cn' , 255)->default('')->comment('中文名');
            $table->string('en' , 255)->default('')->comment('英文名');
            $table->string('value' , 255)->default('')->comment('实际权限');
            $table->string('description' , 500)->default('')->comment('描述');
            $table->string('type' , 255)->default('')->comment('类型：api-接口 view-视图');
            $table->string('method' , 100)->default('GET')->comment('请求方法：仅在 type=api 的时候有效！GET|POST|PUT|PATCH|DELETE ...');
            $table->tinyInteger('is_menu')->default(0)->comment('仅在 type=view 的时候有效，是否在菜单列表显示：0-否 1-是');
            $table->tinyInteger('is_view')->default(0)->comment('仅在 type=view 的时候有效，是否是一个视图：0-否 1-是');
            $table->tinyInteger('enable')->default(1)->comment('是否启用：0-否 1-是');
            $table->unsignedBigInteger('p_id')->default(0)->comment('xq_admin_permission.id');
            $table->string('s_ico' , 500)->default('')->comment('小图标');
            $table->string('b_ico' , 500)->default('')->comment('大图标');
            $table->integer('weight')->default(0)->comment('权重');
            $table->datetime('update_time')->nullable(true);
            $table->datetime('create_time')->nullable(true);
        });
        DB::statement("alter table {$this->table} comment '后台用户-权限表'");
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
