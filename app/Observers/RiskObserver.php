<?php

namespace App\Observers;

use App\Risk;
use Ramsey\Uuid\Uuid;

class RiskObserver
{
    public function creating(Risk $risk)
    {
        $risk->uuid = Uuid::uuid4();
        $risk->status = Risk::STATUS_CREATED;
    }

    /**
     * Handle the risk "created" event.
     *
     * @param  \App\Risk  $risk
     * @return void
     */
    public function created(Risk $risk)
    {
        if (request()->filled('factors')) $risk->factors()->sync(request('factors'));
        if (request()->filled('types')) $risk->types()->sync(request('types'));
    }

    /**
     * Handle the risk "updated" event.
     *
     * @param  \App\Risk  $risk
     * @return void
     */
    public function updated(Risk $risk)
    {
        if (request()->filled('factors')) $risk->factors()->sync(request('factors'));
        if (request()->filled('types')) $risk->types()->sync(request('types'));
    }

    public function deleting(Risk $risk)
    {
        $risk->factors()->detach();
        $risk->types()->detach();
    }
}
