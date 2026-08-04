<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeEmail extends Notification
{
    use Queueable;

    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Welcome to EnzoBank, Your Account Details')
            ->greeting('Hello '.$notifiable->fullname.',')
            ->line('Welcome to EnzoBank! Your account has been successfully verified.')
            ->line('Below are your account details for receiving money in any currency:')
            ->line('')
            ->line('Recipient Full Name: '.($this->user->bankDetails->first()->recipient_name ?? $notifiable->fullname))
            ->line('Bank Name: '.($this->user->bankDetails->first()->bank_name ?? 'N/A'))
            ->line('Account Number / IBAN: '.($this->user->bankDetails->first()->account_number_iban ?? 'N/A'))
            ->line('Country: '.($this->user->bankDetails->first()->country ?? 'N/A'))
            ->line('SWIFT / BIC: '.($this->user->bankDetails->first()->swift_bic ?? 'N/A'))
            ->line('')
            ->line('You can manage your bank details anytime from your dashboard.')
            ->line('')
            ->line('Thank you for choosing EnzoBank.')
            ->action('Go to Dashboard', url('/user/rise/home'))
            ->line('If you did not create this account, please contact support immediately.');
    }
}
