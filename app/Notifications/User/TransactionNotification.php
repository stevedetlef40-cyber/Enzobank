<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class TransactionNotification extends Notification
{
    use Queueable;

    protected $data;

    /**
     * @param  array  $data  Structured transaction notification payload:
     *                       subject, greeting, title, intro, amount, currency, is_credit,
     *                       status, method, date, trx_id, fields[], action_url, action_text
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $d = $this->data;

        $mail = (new MailMessage)
            ->subject($d['subject'] ?? 'Transaction Update - EnzoBank')
            ->greeting($d['greeting'] ?? ('Hello '.$notifiable->fullname.'!'))
            ->line(new HtmlString(
                '<strong style="font-size:18px;color:#0b1f4d;display:block;margin-bottom:6px;">'
                .e($d['title'] ?? 'Transaction Update').'</strong>'
            ));

        if (! empty($d['intro'])) {
            $mail->line($d['intro']);
        }

        $summary = [];
        if (isset($d['amount'])) {
            $sign = ! empty($d['is_credit']) ? '+' : '-';
            $summary[] = ['Amount', $sign.' '.get_amount($d['amount'], $d['currency'] ?? 'USD')];
        }
        if (! empty($d['status'])) {
            $summary[] = ['Status', $d['status']];
        }
        if (! empty($d['method'])) {
            $summary[] = ['Method', $d['method']];
        }
        if (! empty($d['trx_id'])) {
            $summary[] = ['Transaction ID', $d['trx_id']];
        }
        if (! empty($d['date'])) {
            $summary[] = ['Date', $d['date']];
        }

        if (count($summary) > 0) {
            $rows = array_map(function ($r) {
                return '<strong>'.e($r[0]).':</strong> '.e($r[1]);
            }, $summary);
            $mail->line(new HtmlString(
                '<div style="background:#f1f5f9;border-radius:10px;padding:12px 14px;margin:6px 0 12px;line-height:1.8;">'
                .implode('<br>', $rows).'</div>'
            ));
        }

        if (! empty($d['fields']) && is_array($d['fields'])) {
            foreach ($d['fields'] as $field) {
                $mail->line(new HtmlString(
                    '<strong>'.e($field['label']).':</strong> '.e($field['value'])
                ));
            }
        }

        if (! empty($d['action_url'])) {
            $mail->action($d['action_text'] ?? 'View Transaction', $d['action_url']);
        }

        $mail->line('Thank you for banking with EnzoBank.');

        return $mail;
    }

    public function toArray($notifiable)
    {
        return $this->data;
    }
}
