<?php

use App\Division;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Заполнение данными таблицы Подразделений
     *
     * @return void
     */
    public function run()
    {
        Division::updateOrCreate(['name' => 'Руководство'], ['name' => 'Руководство']);
        Division::updateOrCreate(['name' => 'Бухгалтерия'], ['name' => 'Бухгалтерия']);
        Division::updateOrCreate(['name' => 'Отдел кадров'], ['name' => 'Отдел кадров']);
        $itDivision = Division::updateOrCreate(['name' => 'IT'], ['name' => 'IT']);

        Division::updateOrCreate(['name' => 'Разработка', 'parent_id' => $itDivision->id], ['name' => 'Разработка']);
        Division::updateOrCreate(['name' => 'Информационная безопасность', 'parent_id' => $itDivision->id], ['name' => 'Информационная безопасность']);
    }
}
