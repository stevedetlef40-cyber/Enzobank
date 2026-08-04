<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Per-user WhatsApp support number (digits only, e.g. "447464483316").
     * When set, deposit-help links on the site open a chat with this number
     * instead of the general default. Empty/null falls back to the general
     * number (SUPPORT_WHATSAPP env) so existing users are unaffected.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('support_whatsapp')->nullable()->after('vc_fee_override');
        });
    }

    public function down()
    {
        if (Schema::hasColumn('users', 'support_whatsapp')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('support_whatsapp');
            });
        }
    }
};
