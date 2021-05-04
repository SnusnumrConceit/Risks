<?php

namespace App\Observers;

use App\Division;
use Ramsey\Uuid\Uuid;

class DivisionObserver
{
    /**
     * Хук создания Подразделения
     *
     * @param Division $division
     */
    public function creating(Division $division)
    {
        $division->uuid = Uuid::uuid4();
    }
}
