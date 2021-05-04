<?php

namespace App\Policies;

use App\Factor;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FactorPolicy
{
    use HandlesAuthorization;

    /**
     * Доступ на просмотр любых факторов рисков
     *
     * @param \App\User $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('factors_view');
    }

    /**
     * Доступ на просмотр фактора рисков
     *
     * @param \App\User $user
     * @param \App\Factor $factor
     * @return mixed
     */
    public function view(User $user, Factor $factor)
    {
        return $user->hasPermission('factors_view');
    }

    /**
     * Доступ на создание фактора рисков
     *
     * @param \App\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('factors_create');
    }

    /**
     * Доступ на изменение фактора рисков
     *
     * @param \App\User $user
     * @param \App\Factor $factor
     * @return mixed
     */
    public function update(User $user, Factor $factor)
    {
        return $user->hasPermission('factors_edit');
    }

    /**
     * Доступ на удаление фактора рисков
     *
     * @param \App\User $user
     * @param \App\Factor $factor
     * @return mixed
     */
    public function delete(User $user, Factor $factor)
    {
        return $user->hasPermission('factors_delete');
    }
}
