<?php

namespace App\Notifications\User\FundTransfer;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class OwnBankTransferBlockedNotification extends Notification
{
    use Queueable;

    public $user;

    public $beneficiary;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $beneficiary)
    {
        $this->user = $user;
        $this->beneficiary = $beneficiary;
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
        $date = Carbon::now();
        $dateTime = $date->format('Y-m-d h:i:s A');
        $whatsapp = support_whatsapp_number($notifiable);

        return (new MailMessage)
            ->greeting('Hello '.$user->fullname.' !')
            ->subject('Own Bank Transfer Blocked')
            ->line('Your own bank (EnzoBank to EnzoBank) transfer attempt has been blocked.')
            ->line('Beneficiary: '.$this->beneficiary)
            ->line('Reason: This transfer type has been temporarily blocked by the system administrator for security reasons.')
            ->line('If you believe this is an error or need this feature reactivated, please contact our support team for assistance.')
            ->line('Contact us on WhatsApp: https://wa.me/'.$whatsapp)
            ->line('Date And Time: '.$dateTime)
            ->line('Thank you for using our application!')
            ->with(['support_whatsapp' => $whatsapp]);
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
