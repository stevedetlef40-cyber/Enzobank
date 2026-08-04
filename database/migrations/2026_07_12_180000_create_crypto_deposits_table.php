<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCryptoDepositsTable extends Migration
{
    public function up()
    {
        Schema::create('crypto_deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('coin_symbol');
            $table->string('network');
            $table->string('wallet_address');
            $table->decimal('amount_usd', 12, 2);
            $table->decimal('amount_crypto', 20, 8)->nullable();
            $table->string('tx_hash')->nullable();
            $table->string('proof', 255)->nullable();
            $table->string('status')->default('pending');
            $table->text('admin_note')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('crypto_deposits');
    }
}
