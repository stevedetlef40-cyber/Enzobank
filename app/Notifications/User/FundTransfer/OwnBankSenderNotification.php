<?php

namespace App\Notifications\User\FundTransfer;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class OwnBankSenderNotification extends Notification
{
    use Queueable;

    public $user;

    public $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $data)
    {
        $this->user = $user;
        $this->data = $data;
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
        $data = $this->data;
        $trx_id = $this->data->trx_id;
        $date = Carbon::now();
        $dateTime = $date->format('Y-m-d h:i:s A');

        return (new MailMessage)
            ->greeting('Hello '.$user->fullname.' !')
            ->subject('Fund Transfer Successful')
            ->line('Your Fund Transfer request send successfully')
            ->line('Transaction Id: '.$trx_id)
            ->line('Transfer Type: Own Bank Transfer')
            ->line('Request Amount: '.get_amount($data->request_amount).' '.$data->payment_currency)
            ->line('Fees & Charges: '.get_amount($data->total_charge).' '.$data->payment_currency)
            ->line('Receiver Will Get: '.get_amount($data->receive_amount, $data->payment_currency))
            ->line('Total Payable Amount: '.get_amount($data->total_payable, $data->payment_currency))
            ->line('Recipient: '.($data->details->beneficiary->account_holder_name ?? 'N/A'))
            ->line('Recipient Account: '.($data->details->beneficiary->account_number ?? 'N/A'))
            ->line('Status: '.$data->stringStatus->value)
            ->line('Date And Time: '.$dateTime)
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
