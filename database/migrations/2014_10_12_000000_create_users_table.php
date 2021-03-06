<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index();
            $table->string('email')->unique();
            $table->string('last_name', 40);
            $table->string('first_name', 40);
            $table->string('middle_name', 40);
            $table->string('password');
            $table->string('appointment', 50);
            $table->uuid('role_uuid')->nullable();
            $table->unsignedInteger('division_id')->nullable();
            $table->boolean('is_responsible')->default(0)->index();
//            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->foreign('role_uuid')
                ->references('uuid')
                ->on('roles')
                ->onDelete('SET NULL');

            $table->foreign('division_id')
                ->references('id')
                ->on('divisions')
                ->onDelete('SET NULL');
        });

        DB::statement('CREATE FULLTEXT INDEX fio_index ON users(last_name, first_name, middle_name)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE users DROP INDEX fio_index');

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_uuid']);
            $table->dropForeign(['division_id']);
            $table->dropIndex(['uuid']);
            $table->dropIndex(['is_responsible']);
        });

        Schema::dropIfExists('users');
    }
}
