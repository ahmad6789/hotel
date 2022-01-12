<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AddRoomToCat extends Notification
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
    public function toArray($notifiable)
    {
        $user = $notifiable;

        $text = 'المستخدم <strong>'.$this->arr['usname'].'</strong> اضاف الطابق ' .$this->arr['cat'] .'';

        $url_backend = route('backend.users.profile', $this->arr['usid']);
        $url_frontend = route('frontend.users.profile', $this->arr['usid']);

        return [
            'title'         => 'تمت اضافة طابق  ',
            'module'        => 'الفئات',
            'type'          => 'created', // created, published, viewed,
            'icon'          => '',
            'text'          => $text,
            'url_backend'   => $url_backend,
            'url_frontend'  => $url_frontend,
        ];

    }
}
