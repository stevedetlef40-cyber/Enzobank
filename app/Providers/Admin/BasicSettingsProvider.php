<?php

namespace App\Providers\Admin;

class BasicSettingsProvider
{
    public $setting;

    public function __construct($settings = null)
    {
        $this->setting = $settings;
    }

    public function set($settings)
    {
        return $this->setting = $settings;
    }

    public function getData()
    {
        $setting = $this->setting;
        // Treat an empty stdClass (view-share fallback) the same as null.
        if ($setting instanceof \stdClass && ! get_object_vars($setting)) {
            $setting = null;
        }
        if ($setting) {
            return $setting;
        }

        return self::fallbackSettings();
    }

    /**
     * Object with every settings column pre-set to null so
     * `$basic_settings->foo` never throws "Undefined property" when the
     * basic_settings table is empty (e.g. tests, fresh installs).
     * Gate defaults keep a fresh install open: registration allowed,
     * email OTP verification required, SSL not forced, notifications muted.
     */
    public static function fallbackSettings()
    {
        $fallback = new \stdClass;
        foreach (self::FALLBACK_KEYS as $key) {
            $fallback->{$key} = null;
        }
        $fallback->user_registration = true;
        $fallback->email_verification = true;
        $fallback->sms_verification = false;
        $fallback->kyc_verification = false;
        $fallback->secure_password = false;
        $fallback->agree_policy = false;
        $fallback->force_ssl = false;
        $fallback->email_notification = false;
        $fallback->push_notification = false;
        $fallback->mail_activity = false;

        return $fallback;
    }

    public static function get()
    {
        return app(BasicSettingsProvider::class)->getData();
    }

    private const FALLBACK_KEYS = [
        'id', 'site_name', 'site_title', 'base_color', 'secondary_color',
        'otp_exp_seconds', 'timezone', 'user_registration', 'secure_password',
        'agree_policy', 'force_ssl', 'email_verification', 'sms_verification',
        'email_notification', 'push_notification', 'kyc_verification',
        'site_logo_dark', 'site_logo', 'site_fav_dark', 'site_fav',
        'preloader_image', 'mail_config', 'mail_activity',
        'push_notification_config', 'push_notification_activity',
        'broadcast_config', 'broadcast_activity', 'sms_config', 'sms_activity',
        'web_version', 'admin_version', 'created_at', 'updated_at',
        'support_whatsapp',
    ];
}
