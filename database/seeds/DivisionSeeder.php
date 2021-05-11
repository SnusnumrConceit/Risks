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
        $mainDivision = Division::updateOrCreate(['name' => 'Руководство'], ['name' => 'Руководство']);
        Division::updateOrCreate(['name' => 'Бухгалтерия', 'parent_id' => $mainDivision->id], ['name' => 'Бухгалтерия']);
        Division::updateOrCreate(['name' => 'Отдел кадров', 'parent_id' => $mainDivision->id], ['name' => 'Отдел кадров']);
        $itDivision = Division::updateOrCreate(['name' => 'IT', 'parent_id' => $mainDivision->id], ['name' => 'IT']);

        Division::updateOrCreate(['name' => 'Разработка', 'parent_id' => $itDivision->id], ['name' => 'Разработка']);
        Division::updateOrCreate(['name' => 'Информационная безопасность', 'parent_id' => $itDivision->id], ['name' => 'Информационная безопасность']);
    }
}
