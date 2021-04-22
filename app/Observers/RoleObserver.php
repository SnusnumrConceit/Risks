<?php

namespace App\Observers;

use App\Role;
use Ramsey\Uuid\Uuid;

class RoleObserver
{
    public function creating(Role $role)
    {
        $role->uuid = Uuid::uuid4();
    }
}
