<?php

namespace App\Notifications;

use App\Risk;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class RiskExpiredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $beforeExpired, $risk;

    /**
     * Create a new notification instance.
     *
     * @param string $beforeExpired
     * @param \App\Risk $risk
     * @return void
     */
    public function __construct(string $beforeExpired, Risk $risk)
    {
        $this->beforeExpired = $beforeExpired;
        $this->risk = $risk;
    }

    /**
     * Тема письма
     *
     * @return string
     */
    protected function getSubject() : string
    {
        $when = '';

        switch ($this->beforeExpired) {
            case 0: $when = 'today'; break;
            case 1: $when = 'tomorrow'; break;
            case 3: $when = '3_days'; break;
        }

        return __('emails.subjects.risks.expired.' . $when);
    }

    /**
     * Шаблон письма
     *
     * @return string
     */
    protected function getTemplate() : string
    {
        switch ($this->beforeExpired) {
            case 0: return 'emails.risks.expired.today';
            case 1: return 'emails.risks.expired.tomorrow';
            case 3: return 'emails.risks.expired.3_days';
        }
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->from('risks@mail.ru', __('ui.app_name'))
            ->subject($this->getSubject())
            ->markdown($this->getTemplate(), [
                'risk' => $this->risk,
                'user' => $notifiable
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
