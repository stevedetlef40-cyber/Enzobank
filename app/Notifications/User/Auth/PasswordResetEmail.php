<?php

namespace App\Notifications\User\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetEmail extends Notification
{
    use Queueable;

    public $user;

    public $password_reset;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $password_reset)
    {
        $this->user = $user;
        $this->password_reset = $password_reset;
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
        $user = $this->user;
        $password_reset = $this->password_reset;

        return (new MailMessage)
            ->greeting('Hello '.$user->fullname.' !')
            ->subject('Verification Code (Password Reset)')
            ->line('You are trying to reset your password.')
            ->line(mail_otp_box($password_reset->code, 'Your password reset code'))
            ->action('Verify', route('user.password.forgot.code.verify.form', $password_reset->token))
            ->line('Thank you for using our application!');
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
