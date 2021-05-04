<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiskTypeTable extends Migration
{
    /**
     * Запуск миграции
     *
     * @return void
     */
    public function up()
    {
        Schema::create('risk_type', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('risk_id');
            $table->unsignedInteger('type_id');

            $table->foreign('risk_id')
                ->references('id')
                ->on('risks')
                ->onDelete('CASCADE');

            $table->foreign('type_id')
                ->references('id')
                ->on('types')
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
        Schema::table('risk_type', function (Blueprint $table) {
            $table->dropForeign(['risk_id']);
            $table->dropForeign(['type_id']);
        });

        Schema::dropIfExists('risk_type');
    }
}
