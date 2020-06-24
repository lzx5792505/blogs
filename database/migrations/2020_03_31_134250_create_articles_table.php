<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',55)->comment('标题');
            $table->unsignedInteger('cate_id')->comment('分类ID');
            $table->unsignedInteger('user_id')->comment('创建人ID');
            $table->boolean('sort')->comment('排序');
            $table->boolean('models')->comment('文章所属模块');
            $table->string('cover')->comment('封面');
            $table->string('Keywords', 45)->comment('关键词');
            $table->string('describe')->comment('描述');
            $table->string('content')->comment('内容');
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
        Schema::dropIfExists('articles');
    }
}
