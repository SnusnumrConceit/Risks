<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSummaAndDamageColumnsToRisksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('risks', function (Blueprint $table) {
            $table->decimal('summa', 10, 2)
                ->after('description')
                ->default(0)
                ->index();
            $table->decimal('damage', 10, 2)
                ->after('summa')
                ->default(0)
                ->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('risks', function (Blueprint $table) {
            $table->dropIndex(['summa']);
            $table->dropIndex(['damage']);

            $table->dropColumn(['summa', 'damage']);
        });
    }
}
