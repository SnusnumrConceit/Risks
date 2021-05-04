<?php

use Illuminate\Database\Seeder;

class FactorSeeder extends Seeder
{
    /**
     * Заполнение записями таблицы факторов рисков
     *
     * @return void
     */
    public function run()
    {
        \App\Factor::updateOrCreate(['name' => 'Персонал'],                 ['name' => 'Персонал']);
        \App\Factor::updateOrCreate(['name' => 'Системы и оборудование'],   ['name' => 'Системы и оборудование']);
        \App\Factor::updateOrCreate(['name' => 'Организация и управление'], ['name' => 'Организация и управление']);
        \App\Factor::updateOrCreate(['name' => 'Внешняя среда'],            ['name' => 'Внешняя среда']);
    }
}
