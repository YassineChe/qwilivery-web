<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyAdminNewDelivery extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    public $delivery;

    public function __construct($delivery)
    {
        $this->delivery = $delivery;
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
                    ->subject(config('app.name').' | Nouveau livreur 🛵')
                    ->greeting('Bonjour, '. $notifiable->last_name .' !')
                    ->line('Un nouveau livreur s\'est inscrit sur '. config('app.name'))
                    ->line('Nom complet: '. $this->delivery->first_name .' '. $this->delivery->last_name .' \n'
                    .'E-mail: '. $this->delivery->email .'\n'
                    .'Numéro téléphone: '. $this->delivery->phone_number .'\n'
                    .'Expérience: '. $this->delivery->experience );
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
