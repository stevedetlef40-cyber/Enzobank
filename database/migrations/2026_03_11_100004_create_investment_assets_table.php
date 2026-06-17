<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investment_assets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('symbol')->unique();
            $table->enum('asset_type', ['stock','fund','bond','crypto','cash'])->default('stock');
            $table->string('risk_level')->nullable();
            $table->decimal('current_price', 19, 6)->default(0);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investment_assets');
    }
};

