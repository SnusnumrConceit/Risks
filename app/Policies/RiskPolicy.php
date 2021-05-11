<?php

namespace App\Policies;

use App\Risk;
use App\User;
use App\Division;
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
            && $this->canRiskPersonalAccess($user, $risk);
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
            && $this->canRiskPersonalAccess($user, $risk);
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
            && $this->canRiskPersonalAccess($user, $risk);
    }

    /**
     * Проверка, имеется ли у пользователя персональный доступ к риску
     *
     * @param \App\User $user
     * @param \App\Risk $risk
     * @return bool
     */
    private function canRiskPersonalAccess(User $user, Risk $risk)
    {
        return $user->division_id === $risk->division_id
            || (
                $user->is_responsible
                && in_array($risk->division_id, Division::getDescendantsIds($user->division_id, $user->division->level))
            );
    }
}
