<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Запуск сидеров
     *
     * @return void
     */
    public function run()
    {
        $this->call(FactorSeeder::class);
        $this->call(TypeSeeder::class);
        $this->call(DivisionSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
    }
}
