<?php

namespace App\Policies;

use App\User;
use App\Division;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Доступ на просмотр любых пользователей
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('users_view')
            || $user->hasPermission('users_view_own');
    }

    /**
     * Доступ на просмотр пользователя
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function view(User $user, User $model)
    {
        if ($user->hasPermission('full')) return true;

        if ($user->id === $model->id) return false;

        if ( $user->hasPermission('users_view') ) return true;

        return $user->hasPermission('users_view_own')
            && $this->canRiskPersonalAccess($user, $model);
    }

    /**
     * Доступ на создание пользователя
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('users_create');
    }

    /**
     * Доступ на обновление пользователя
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        if ($user->hasPermission('full')) return true;

        if ($user->id === $model->id) return false;

        if ( $user->hasPermission('users_edit') ) return true;

        return $user->hasPermission('users_edit_own')
            && $this->canRiskPersonalAccess($user, $model);
    }

    /**
     * Доступ на удаление пользователя
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        if ($user->id === $model->id) return false;

        if ( $user->hasPermission('users_delete') ) return true;

        return $user->hasPermission('users_delete_own')
            && $this->canRiskPersonalAccess($user, $model);
    }

    /**
     * Проверка, имеется ли у пользователя персональный доступ к подразделению
     *
     * @param \App\User $user
     * @param \App\User $model
     * @return bool
     */
    private function canRiskPersonalAccess(User $user, User $model)
    {
        return $user->division_id === $model->id
            || (
                $user->is_responsible
                && in_array($model->division_id, Division::getDescendantsIds($user->division_id, $user->division->level))
            );
    }
}
