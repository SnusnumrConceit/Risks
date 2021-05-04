<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFactorRiskTable extends Migration
{
    /**
     * Запуск миграции
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factor_risk', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('factor_id');
            $table->unsignedBigInteger('risk_id');

            $table->foreign('factor_id')
                ->references('id')
                ->on('factors')
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
        Schema::table('factor_risk', function (Blueprint $table) {
            $table->dropForeign(['factor_id']);
            $table->dropForeign(['risk_id']);
        });

        Schema::dropIfExists('factor_risk');
    }
}
