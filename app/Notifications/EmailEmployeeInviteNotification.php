<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Crypt;

class EmailEmployeeInviteNotification extends Notification
{
    use Queueable;

    public $url;
    public function __construct($uuid)
    {
        $this->url = 'https://admin.upmenu.ru/register/'.$uuid;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)->markdown('vendor/notifications/email', [
            'greeting' => 'Привет!',
            'actionText' => 'Принять',
            'actionUrl' => $this->url,
            'introLines' => [
                'Перейдите по ссылке для принятия приглошения',
            ],
            //'salutation' => $this->url,
            'displayableActionUrl' => 'Ссылка',
        ]);
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
