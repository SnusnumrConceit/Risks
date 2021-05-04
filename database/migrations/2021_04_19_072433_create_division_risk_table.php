<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDivisionRiskTable extends Migration
{
    /**
     * Запуск миграции
     *
     * @return void
     */
    public function up()
    {
        Schema::create('division_risk', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('division_id');
            $table->unsignedBigInteger('risk_id');

            $table->foreign('division_id')
                ->references('id')
                ->on('divisions')
                ->onDelete('CASCADE');

            $table->foreign('risk_id')
                ->references('id')
                ->on('risks')
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
        Schema::table('division_risk', function (Blueprint $table) {
            $table->dropForeign(['division_id']);
            $table->dropForeign(['risk_id']);
        });

        Schema::dropIfExists('division_risk');
    }
}
