<?php

namespace App\Policies;

use App\Risk;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RiskPolicy
{
    use HandlesAuthorization;

    /**
     * Доступ на просмотр любых рисков
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('risks_view')
            || $user->hasPermission('risks_view_own');
    }

    /**
     * Доступ на просмотр рисков
     *
     * @param  \App\User  $user
     * @param  \App\Risk  $risk
     * @return mixed
     */
    public function view(User $user, Risk $risk)
    {
        if ( $user->hasPermission('risks_view') ) return true;

        return $user->hasPermission('risks_view_own')
            && $user->division_id === $risk->division_id;
    }

    /**
     * Доступ на создание рисков
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('risks_create');
    }

    /**
     * Доступ на обновление рисков
     *
     * @param  \App\User  $user
     * @param  \App\Risk  $risk
     * @return mixed
     */
    public function update(User $user, Risk $risk)
    {
        if ( $user->hasPermission('risks_edit') ) return true;

        return $user->hasPermission('risks_edit_own')
            && $user->division_id === $risk->division_id;
    }

    /**
     * Доступ на удаление рисков
     *
     * @param  \App\User  $user
     * @param  \App\Risk  $risk
     * @return mixed
     */
    public function delete(User $user, Risk $risk)
    {
        if ( $user->hasPermission('risks_delete') ) return true;

        return $user->hasPermission('risks_delete_own')
            && $user->division_id === $risk->division_id;
    }
}
