<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('资源id');
            $table->string('file_name', 35)->comment('资源名称');
            $table->string('file_url')->comment('资源链接');
            $table->integer('user_id')->unique()->comment('用户id');
            $table->integer('cate_id')->comment('分类id');
            $table->integer('sort')->comment('排序');
            $table->string('code', 10)->comment('标识');
            $table->tinyInteger('status')->comment('状态1：上架，2：下架');
            $table->tinyInteger('file_type')->comment('资源类型');
            $table->string('Keywords', 45)->comment('关键词');
            $table->string('describe')->comment('描述');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
