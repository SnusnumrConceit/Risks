<?php

use App\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::updateOrCreate(['name' => 'full', 'is_group' => false,  'group' => null], ['name' => 'full']);

        /** Права для подразделений */
        Permission::updateOrCreate(['name' => 'divisions',            'is_group' => true,  'group' => null],        ['name' => 'divisions']);
        Permission::updateOrCreate(['name' => 'divisions_view',       'is_group' => false, 'group' => 'divisions'], ['name' => 'divisions_view']);
        Permission::updateOrCreate(['name' => 'divisions_view_own',   'is_group' => false, 'group' => 'divisions'], ['name' => 'divisions_view_own']);
        Permission::updateOrCreate(['name' => 'divisions_create',     'is_group' => false, 'group' => 'divisions'], ['name' => 'divisions_create']);
        Permission::updateOrCreate(['name' => 'divisions_edit',       'is_group' => false, 'group' => 'divisions'], ['name' => 'divisions_edit']);
        Permission::updateOrCreate(['name' => 'divisions_edit_own',   'is_group' => false, 'group' => 'divisions'], ['name' => 'divisions_edit_own']);
        Permission::updateOrCreate(['name' => 'divisions_delete',     'is_group' => false, 'group' => 'divisions'], ['name' => 'divisions_delete']);
        Permission::updateOrCreate(['name' => 'divisions_delete_own', 'is_group' => false, 'group' => 'divisions'], ['name' => 'divisions_delete_own']);

        /** Права для факторов рисков */
        Permission::updateOrCreate(['name' => 'factors',        'is_group' => true,  'group' => null],      ['name' => 'factors']);
        Permission::updateOrCreate(['name' => 'factors_view',   'is_group' => false, 'group' => 'factors'], ['name' => 'factors_view']);
        Permission::updateOrCreate(['name' => 'factors_create', 'is_group' => false, 'group' => 'factors'], ['name' => 'factors_create']);
        Permission::updateOrCreate(['name' => 'factors_edit',   'is_group' => false, 'group' => 'factors'], ['name' => 'factors_edit']);
        Permission::updateOrCreate(['name' => 'factors_delete', 'is_group' => false, 'group' => 'factors'], ['name' => 'factors_delete']);

        /** Права для подразделений */
        Permission::updateOrCreate(['name' => 'risks',            'is_group' => true,  'group' => null],    ['name' => 'risks']);
        Permission::updateOrCreate(['name' => 'risks_view',       'is_group' => false, 'group' => 'risks'], ['name' => 'risks_view']);
        Permission::updateOrCreate(['name' => 'risks_view_own',   'is_group' => false, 'group' => 'risks'], ['name' => 'risks_view_own']);
        Permission::updateOrCreate(['name' => 'risks_create',     'is_group' => false, 'group' => 'risks'], ['name' => 'risks_create']);
        Permission::updateOrCreate(['name' => 'risks_edit',       'is_group' => false, 'group' => 'risks'], ['name' => 'risks_edit']);
        Permission::updateOrCreate(['name' => 'risks_edit_own',   'is_group' => false, 'group' => 'risks'], ['name' => 'risks_edit_own']);
        Permission::updateOrCreate(['name' => 'risks_delete',     'is_group' => false, 'group' => 'risks'], ['name' => 'risks_delete']);
        Permission::updateOrCreate(['name' => 'risks_delete_own', 'is_group' => false, 'group' => 'risks'], ['name' => 'risks_delete_own']);

        /** Права для ролей */
        Permission::updateOrCreate(['name' => 'roles',        'is_group' => true,  'group' => null],    ['name' => 'roles']);
        Permission::updateOrCreate(['name' => 'roles_view',   'is_group' => false, 'group' => 'roles'], ['name' => 'roles_view']);
        Permission::updateOrCreate(['name' => 'roles_create', 'is_group' => false, 'group' => 'roles'], ['name' => 'roles_create']);
        Permission::updateOrCreate(['name' => 'roles_edit',   'is_group' => false, 'group' => 'roles'], ['name' => 'roles_edit']);
        Permission::updateOrCreate(['name' => 'roles_delete', 'is_group' => false, 'group' => 'roles'], ['name' => 'roles_delete']);

        /** Права для видов рисков */
        Permission::updateOrCreate(['name' => 'types',        'is_group' => true,  'group' => null],    ['name' => 'types']);
        Permission::updateOrCreate(['name' => 'types_view',   'is_group' => false, 'group' => 'types'], ['name' => 'types_view']);
        Permission::updateOrCreate(['name' => 'types_create', 'is_group' => false, 'group' => 'types'], ['name' => 'types_create']);
        Permission::updateOrCreate(['name' => 'types_edit',   'is_group' => false, 'group' => 'types'], ['name' => 'types_edit']);
        Permission::updateOrCreate(['name' => 'types_delete', 'is_group' => false, 'group' => 'types'], ['name' => 'types_delete']);

        /** Права для пользователей */
        Permission::updateOrCreate(['name' => 'users',            'is_group' => true,  'group' => null],    ['name' => 'users']);
        Permission::updateOrCreate(['name' => 'users_view',       'is_group' => false, 'group' => 'users'], ['name' => 'users_view']);
        Permission::updateOrCreate(['name' => 'users_view_own',   'is_group' => false, 'group' => 'users'], ['name' => 'users_view_own']);
        Permission::updateOrCreate(['name' => 'users_create',     'is_group' => false, 'group' => 'users'], ['name' => 'users_create']);
        Permission::updateOrCreate(['name' => 'users_edit',       'is_group' => false, 'group' => 'users'], ['name' => 'users_edit']);
        Permission::updateOrCreate(['name' => 'users_edit_own',   'is_group' => false, 'group' => 'users'], ['name' => 'users_edit_own']);
        Permission::updateOrCreate(['name' => 'users_delete',     'is_group' => false, 'group' => 'users'], ['name' => 'users_delete']);
        Permission::updateOrCreate(['name' => 'users_delete_own', 'is_group' => false, 'group' => 'users'], ['name' => 'users_delete_own']);
    }
}
