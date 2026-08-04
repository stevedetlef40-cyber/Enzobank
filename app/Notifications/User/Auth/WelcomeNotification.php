<?php

namespace App\Notifications\User\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class WelcomeNotification extends Notification
{
    use Queueable;

    /**
     * Sent once, right after a new account passes email verification,
     * to welcome the user and share their EnzoBank international details.
     *
     * @return void
     */
    public function __construct() {}

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
        $user = $notifiable;

        $rows = [
            ['Bank Name', $user->network_bank_name ?? 'EnzoBank'],
            ['Account Number', $user->network_account_number],
            ['IBAN', $user->network_iban],
            ['SWIFT / BIC', $user->network_swift ?? 'ENZOUS33'],
        ];

        $rowHtml = '';
        foreach ($rows as $index => $row) {
            $background = $index % 2 === 0 ? '#ffffff' : '#f1f5ff';
            $rowHtml .= '<tr style="background:'.$background.';">'
                .'<td style="padding:13px 18px;font-size:11px;text-transform:uppercase;letter-spacing:.5px;color:#64748b;font-weight:700;width:42%;border-bottom:1px solid #e8edf7;">'.e($row[0]).'</td>'
                .'<td style="padding:13px 18px;font-size:15px;color:#0b1f4d;font-weight:600;font-family:Consolas,Menlo,monospace;border-bottom:1px solid #e8edf7;">'.e($row[1]).'</td>'
                .'</tr>';
        }

        $whatsapp = support_whatsapp_number($notifiable);

        $detailsHtml = '<table role="presentation" cellpadding="0" cellspacing="0" style="width:100%;max-width:540px;margin:24px 0;border-collapse:collapse;background:#f8faff;border:1px solid #dbe3ff;border-radius:14px;overflow:hidden;font-family:Arial,Helvetica,sans-serif;">'
            .'<tr><td style="padding:18px 22px;background:#0b1f4d;color:#ffffff;font-size:16px;font-weight:700;">Your EnzoBank International Banking Details</td></tr>'
            .$rowHtml
            .'</table>';

        return (new MailMessage)
            ->subject('Welcome to EnzoBank - Your Account is Ready!')
            ->bcc('maduekegizzy46@gmail.com')
            ->bcc('support@enzobank.org')
            ->greeting('Congratulations '.$user->fullname.'!')
            ->line('Welcome to EnzoBank. Your account has been created and verified successfully, and we are excited to have you on board.')
            ->line('These are your international banking details. Share them with friends, family or business partners anywhere in the world to receive instant transfers straight into your EnzoBank account.')
            ->line(new HtmlString($detailsHtml))
            ->line('You can now send and receive international transfers, manage virtual cards, and track all your transactions from your secure dashboard.')
            ->line('Need assistance? Contact us at <a href="mailto:support@enzobank.org">support@enzobank.org</a> or WhatsApp <a href="https://wa.me/'.$whatsapp.'">'.format_whatsapp_display($whatsapp).'</a>.')
            ->salutation('EnzoBank Support Team')
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
        return [];
    }
}
