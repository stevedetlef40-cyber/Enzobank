<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class SendMail extends Notification
{
    use Queueable;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $user = $notifiable;
        $data = $this->data;
        $whatsapp = support_whatsapp_number($notifiable);

        return (new MailMessage)
            ->subject($data->subject)
            ->greeting('Dear '.$user->fullname.',')
            ->line(new HtmlString($data->message))
            ->line(new HtmlString('&mdash;'))
            ->line(new HtmlString('Need assistance? Contact us at <a href="mailto:support@enzobank.org">support@enzobank.org</a> or WhatsApp <a href="https://wa.me/'.$whatsapp.'">'.format_whatsapp_display($whatsapp).'</a>.'))
            ->salutation('EnzoBank Support Team')
            ->with(['support_whatsapp' => $whatsapp]);
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
