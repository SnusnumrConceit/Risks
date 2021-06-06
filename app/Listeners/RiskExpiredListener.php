<?php

namespace App\Listeners;

use App\Risk;
use App\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\RiskExpiredNotification;
//use Illuminate\Queue\InteractsWithQueue;
//use Illuminate\Contracts\Queue\ShouldQueue;

class RiskExpiredListener
{
    private $beforeExpired, $risks, $responsibles;

    /**
     * Перехват события
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $this->providingHandleData($event);

        foreach ($this->risks as $risk) {
            $divisionsIds = explode(
                '/',
                substr($risk->division->path, 0, strpos($risk->division->path, (string) $risk->division_id) + 1)
            );

            Notification::send($this->getParticipants($divisionsIds), new RiskExpiredNotification($this->beforeExpired, $risk));
        }
    }

    /**
     * Получить получателей по подразделениям
     *
     * @param array $divisionsIds
     * @return array
     */
    protected function getParticipants(array $divisionsIds = [])
    {
        $participants = [];

        foreach ($this->responsibles as $responsible) {
            if (! in_array($responsible->division_id, $divisionsIds)) continue;

            array_push($participants, $responsible);
        }

        return $participants;
    }

    /**
     * Инициализация переменных необходимыми данными
     *
     * @param $event
     */
    protected function providingHandleData($event)
    {
        $this->beforeExpired = $event;

        $this->risks = Risk::with('division')
            ->whereIn('status', [Risk::STATUS_CREATED, Risk::STATUS_PROCESSING])
            ->whereBetween(
                'expired_at',
                [
                    now()->addDays($this->beforeExpired)->toDateString() . ' 00:00:00',
                    now()->addDays($this->beforeExpired)->toDateString() . ' 23:59:59'
                ]
            )
            ->get();

        $this->responsibles = User::responsible()->get();
    }
}
