<?php

namespace App\Policies;

use App\User;
use App\Division;
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
            && $this->canRiskPersonalAccess($user, $division);
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
            && $this->canRiskPersonalAccess($user, $division);
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
            && $this->canRiskPersonalAccess($user, $division);
    }

    /**
     * Проверка, имеется ли у пользователя персональный доступ к подразделению
     *
     * @param \App\User $user
     * @param \App\Division $division
     * @return bool
     */
    private function canRiskPersonalAccess(User $user, Division $division)
    {
        return $user->division_id === $division->id
            || (
                $user->is_responsible
                && in_array($division->id, Division::getDescendantsIds($user->division_id, $user->division->level))
            );
    }
}
