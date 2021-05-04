<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDivisionUserTable extends Migration
{
    /**
     * Запуск миграции
     *
     * @return void
     */
    public function up()
    {
        Schema::create('division_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('division_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('is_responsible')
                ->comment('признак является ли пользователь ответственным за риск данного подразделения');
            $table->timestamps();

            $table->foreign('division_id')
                ->references('id')
                ->on('divisions')
                ->onDelete('CASCADE');

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('CASCADE');
        });
    }

    /**
     * Откат миграции
     *
     * @return void
     */
    public function down()
    {
        Schema::table('division_user', function (Blueprint $table) {
            $table->dropForeign(['division_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('division_user');
    }
}
