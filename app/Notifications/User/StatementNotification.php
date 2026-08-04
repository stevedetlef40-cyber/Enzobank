<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StatementNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $pdfPath;

    public $statementPeriod;

    public function __construct($pdfPath, $statementPeriod)
    {
        $this->pdfPath = $pdfPath;
        $this->statementPeriod = $statementPeriod;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $basic_settings = \App\Providers\Admin\BasicSettingsProvider::get();
        $siteName = $basic_settings->site_name ?? config('app.name');

        return (new MailMessage)
            ->subject("{$siteName} - Your Bank Statement is Ready")
            ->greeting("Hello {$notifiable->fullname},")
            ->line("Your requested bank statement for **{$this->statementPeriod}** has been generated.")
            ->line('Please find the PDF statement attached to this email.')
            ->line('If you did not request this statement, please contact our support team immediately.')
            ->action('View Account', url('/user/statements'))
            ->salutation("{$siteName} Team")
            ->attach($this->pdfPath, [
                'as' => "{$siteName}-statement-{$this->statementPeriod}.pdf",
                'mime' => 'application/pdf',
            ]);
    }
}
