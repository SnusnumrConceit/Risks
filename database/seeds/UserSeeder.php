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
            'last_name'   => '',
            'first_name'  => 'Admin',
            'middle_name' => '',
            'appointment' => '',
            'password'    => 'test1234',
            'email'       => 'test@test.ru',
            'role_uuid'   => \App\Role::whereName('Администратор')->first()->uuid
        ], ['email' => 'test@test.ru']);
    }
}
