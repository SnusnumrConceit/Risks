<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Заполнение пользователями
     *
     * @return void
     */
    public function run()
    {
        \App\User::updateOrCreate([
            'last_name'   => 'Admin',
            'first_name'  => '',
            'middle_name' => '',
            'appointment' => '',
            'password'    => bcrypt('test1234'),
            'email'       => 'test@test.ru',
            'role_uuid'   => \App\Role::whereName('Администратор')->first()->uuid
        ], ['last_name' => 'Admin']);
    }
}
