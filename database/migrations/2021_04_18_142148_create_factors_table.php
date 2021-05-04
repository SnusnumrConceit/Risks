<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFactorsTable extends Migration
{
    /**
     * Запуск миграции создания таблицы Factors с созданием внешних ключей
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factors', function (Blueprint $table) {
            $table->tinyIncrements('id');
            $table->string('name', 100)->index();
            $table->unsignedTinyInteger('parent_id')->nullable();

            $table->foreign('parent_id')
                ->references('id')
                ->on('factors')
                ->onDelete('SET NULL')
                ->onUpdate('SET NULL');
        });
    }

    /**
     * Откат миграции
     *
     * @return void
     */
    public function down()
    {
        Schema::table('factors', function (Blueprint $table) {
            $table->dropIndex(['name']);

            $table->dropForeign(['parent_id']);
        });

        Schema::dropIfExists('factors');
    }
}
