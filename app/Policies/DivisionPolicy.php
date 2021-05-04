<?php

namespace App\Policies;

use App\Division;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DivisionPolicy
{
    use HandlesAuthorization;

    /**
     * Доступ на просмотр любых Подразделений
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('divisions_view')
            || $user->hasPermission('divisions_view_own');
    }

    /**
     * Доступ на просмотр Подразделения
     *
     * @param  \App\User  $user
     * @param  \App\Division  $division
     * @return mixed
     */
    public function view(User $user, Division $division)
    {
        if ( $user->hasPermission('divisions_view') ) return true;

        return $user->hasPermission('divisions_view_own')
            && $user->divisions()->where('id', $division->id)->exists();
    }

    /**
     * Доступ на создание Подразделения
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('divisions_create');
    }

    /**
     * Доступ на обновление Подразделения
     *
     * @param  \App\User  $user
     * @param  \App\Division  $division
     * @return mixed
     */
    public function update(User $user, Division $division)
    {
        if ( $user->hasPermission('divisions_edit') ) return true;

        return $user->hasPermission('divisions_edit_own')
            && $user->divisions()->where(['id' => $division->id, 'is_responsible' => true])->exists();
    }

    /**
     * Доступ на удаление Подразделения
     *
     * @param  \App\User  $user
     * @param  \App\Division  $division
     * @return mixed
     */
    public function delete(User $user, Division $division)
    {
        if ( $user->hasPermission('divisions_delete') ) return true;

        return $user->hasPermission('divisions_delete_own')
            && $user->divisions()->where(['id' => $division->id, 'is_responsible' => true])->exists();
    }
}
