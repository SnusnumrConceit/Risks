<?php

use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Заполнение записями таблицы видов рисков
     *
     * @return void
     */
    public function run()
    {
        \App\Type::updateOrCreate(['name' => 'Операционный'],   ['name' => 'Операционный']);
        \App\Type::updateOrCreate(['name' => 'Правовой'],       ['name' => 'Правовой']);
        \App\Type::updateOrCreate(['name' => 'Репутационный'],  ['name' => 'Репутационный']);
        \App\Type::updateOrCreate(['name' => 'Кредитный'],      ['name' => 'Кредитный']);
        \App\Type::updateOrCreate(['name' => 'Рыночный'],       ['name' => 'Рыночный']);
        \App\Type::updateOrCreate(['name' => 'Хозяйственный'],  ['name' => 'Хозяйственный']);
        \App\Type::updateOrCreate(['name' => 'Имущественый'],   ['name' => 'Имущественый']);
        \App\Type::updateOrCreate(['name' => 'Управленческий'], ['name' => 'Управленческий']);
    }
}
