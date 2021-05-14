<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyAdminNewRestaurant extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $restaurant;

    public function __construct($restaurant)
    {
        $this->restaurant = $restaurant;
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
                    ->subject(config('app.name').' | Nouveau restaurant 🍕')
                    ->greeting('Bonjour, '. $notifiable->last_name .' !')
                    ->line("Un nouveau restaurant s'est inscrit sur ". config('app.name'))
                    ->line('Nom du restaurant: '. $this->restaurant->name)
                    ->line('E-mail: '. $this->restaurant->email)
                    ->line('Numéro téléphone: '. $this->restaurant->phone_number)
                    ->line('Adresse: '. $this->restaurant->address);
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
