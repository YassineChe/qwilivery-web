<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetEmail extends Notification
{
    use Queueable;

    // Set Data
    public $token;
    public $email;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($email, $token)
    {
        $this->token = $token;
        $this->email = $email;
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
            ->subject(config('app.name').' | Réinitialisez votre mot de passe 🔑')
            ->greeting('Bonjour!')
            ->line("Cet e-mail vous a été envoyé suite à une demande de réinitialisation du mot de passe de votre compte. Pour réinitialiser votre mot de passe, cliquez sur le lien ci-dessous. Si vous n'avez pas effectué cette action, Veuillez ignorer cet email")
            ->action('Réinitialisez votre mot de passe', url('/reset-password/' . $this->token));
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
