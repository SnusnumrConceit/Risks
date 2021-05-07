<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRisksTable extends Migration
{
    /**
     * Запуск миграции
     *
     * @return void
     */
    public function up()
    {
        Schema::create('risks', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index();
            $table->string('name');
            $table->text('description');
            $table->string('level', 15)->index();
            $table->string('status', 15)->index();
            $table->timestamp('expired_at')
                ->index()
                ->comment('дата и время завершения риска');
            $table->unsignedTinyInteger('likelihood')
                ->index()
                ->comment('вероятность в баллах');
            $table->unsignedTinyInteger('impact')
                ->index()
                ->comment('влияние в баллах');
            $table->unsignedInteger('division_id')->nullable();
            $table->timestamps();

            $table->index(['created_at']);

            $table->foreign('division_id')
                ->references('id')
                ->on('divisions')
                ->onDelete('SET NULL');
        });

       DB::statement('CREATE FULLTEXT INDEX risks_fulltext_search ON risks(name, description)');
    }

    /**
     * Откат миграции
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE risks DROP INDEX risks_fulltext_search');

        Schema::table('risks', function (Blueprint $table) {
            $table->dropForeign(['division_id']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['expired_at']);
            $table->dropIndex(['likelihood']);
            $table->dropIndex(['impact']);
            $table->dropIndex(['status']);
            $table->dropIndex(['uuid']);
        });

        Schema::dropIfExists('risks');
    }
}
