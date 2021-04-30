<?php

namespace App\Policies;

use App\Type;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TypePolicy
{
    use HandlesAuthorization;

    /**
     * Доступ на просмотр любых видов рисков
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission('types_view');
    }

    /**
     * Доступ на просмотр вида рисков
     *
     * @param  \App\User  $user
     * @param  \App\Type  $type
     * @return mixed
     */
    public function view(User $user, Type $type)
    {
        return $user->hasPermission('types_view');
    }

    /**
     * Доступ на создание вида рисков
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('types_create');
    }

    /**
     * Доступ на обновление вида рисков
     *
     * @param  \App\User  $user
     * @param  \App\Type  $type
     * @return mixed
     */
    public function update(User $user, Type $type)
    {
        return $user->hasPermission('types_edit');
    }

    /**
     * Доступ на удаление вида рисков
     *
     * @param  \App\User  $user
     * @param  \App\Type  $type
     * @return mixed
     */
    public function delete(User $user, Type $type)
    {
        return $user->hasPermission('types_delete');
    }
}
