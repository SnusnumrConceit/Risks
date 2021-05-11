<?php

use App\Role;
use App\Permission;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Запуск создания/обновления ролей с подготовленным набором прав
     *
     * @return void
     */
    public function run()
    {
        $administrator = Role::updateOrCreate(['name' => 'Администратор'], ['name' => 'Администратор']);

        $administrator->permissions()->sync(
            Permission::whereIn('name', ['full'])
                ->pluck('id')
        );

        $user = Role::updateOrCreate(['name' => 'Пользователь'], ['name' => 'Пользователь']);

        $user->permissions()->sync(
            Permission::whereIn('name', [
                'risks_view_own', 'risks_edit_own', 'risks_delete_own', 'risks_create',
                'divisions_view_own', 'divisions_edit_own', 'divisions_delete_own',
            ])->pluck('id')
        );
    }
}
