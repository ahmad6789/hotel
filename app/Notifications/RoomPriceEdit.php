<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RoomPriceEdit extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $arr;
    public function __construct($arr)
    {
       $this->arr=$arr;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */


    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        $user = $notifiable;

        $text = 'المستخدم <strong>'.$this->arr['usname'].'</strong> حجز الغرفة '.$this->arr['roomcode'] .' وعدل السعر من'.$this->arr['oldprice'] .' الى  '.$this->arr['newprice'].'';

        $url_backend = route('backend.users.profile', $this->arr['usid']);
        $url_frontend = route('frontend.users.profile', $this->arr['usid']);

        return [
            'title'         => ' حجز غرفة مع نعديل السعر',
            'module'        => 'حجز الغرف',
            'type'          => 'created', // created, published, viewed,
            'icon'          => '',
            'text'          => $text,
            'url_backend'   => $url_backend,
            'url_frontend'  => $url_frontend,
        ];
    }
}
