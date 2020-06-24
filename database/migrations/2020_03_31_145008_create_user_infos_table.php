<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_infos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('username')->comment('昵称');
            $table->string('real_name')->comment('真实姓名');
            $table->string('phone',11)->comment('电话');
            $table->string('email',35)->comment('邮箱');
            $table->timestamp('birthday', 0)->comment('生日');
            $table->boolean('gender')->comment('性别 1：男，2：女');
            $table->string('id_card',18)->comment('身份证');
            $table->string('address')->comment('地址');
            $table->string('file_url')->comment('头像');
            $table->boolean('marriage')->comment('婚姻状态1：已婚，2：未婚');
            $table->boolean('status')->comment(' 1：开启，2：禁用');
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
        Schema::dropIfExists('user_infos');
    }
}
