<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Notifications\Messages\CustomMessage;
use App\Notifications\Panel;

class UserWelcomePasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $password;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($password)
    {
        //
        $this->password = $password;
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
     * @return \Illuminate\Notifications\Messages\CustomMessage
     */
    public function toMail($notifiable)
    {
        return (new CustomMessage)
                    ->subject('Cadastro Efetuado com Sucesso!')
                    ->line('Bem vindo.')
                    ->line('Seu cadastro foi efetuado com sucesso em nosso sistema.')
                    ->line((new Panel("Sua senha de acesso gerada automaticamente é: <br/><b>".$this->password.'</b>')))
                    ->line('Para acessar sua conta clique no botão abaixo.')
                    ->action('Acessar', route('login'))
                    ->line('Obrigado pela preferência!')
                    ->markdown('vendor.notifications.welcome_password_email', ['password' => $this->password]);
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
