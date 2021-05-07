<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDivisionsTable extends Migration
{
    /**
     * Запуск миграции создания таблицы Подразделений:
     * - создание индекса на uuid
     * - создание индекса на name
     * - создание индекса на path
     * - создание внешнего ключа parent_id
     *
     * @return void
     */
    public function up()
    {
        Schema::create('divisions', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->index();
            $table->string('name', 50)->index();
            $table->unsignedTinyInteger('level')->default(0);
            $table->string('path')->nullable()->index();
            $table->unsignedInteger('parent_id')->nullable();
            $table->timestamps();

            $table->foreign('parent_id')
                ->references('id')
                ->on('divisions')
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
        Schema::table('divisions', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['uuid']);
            $table->dropIndex(['path']);

            $table->dropForeign(['parent_id']);
        });

        Schema::dropIfExists('divisions');
    }
}
