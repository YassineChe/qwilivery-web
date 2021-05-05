<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyRestaurantAccount extends Notification
{
    use Queueable;

    // Set Data
    public $restaurant;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
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
            ->subject(config('app.name').' | Information d\'authentification 🔑')
            ->greeting('Bonjour!')
            ->line('Vous trouverez ci-joint les informations d\'authentification')
            ->line('Email: ' . $this->restaurant['email'])
            ->line('Password: ' . $this->restaurant['password'])
            ->action('Connexion', url('/login'));
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
