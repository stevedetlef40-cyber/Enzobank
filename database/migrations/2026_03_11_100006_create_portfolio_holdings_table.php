<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolio_holdings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_id')->constrained('portfolios')->cascadeOnDelete();
            $table->foreignId('investment_asset_id')->constrained('investment_assets')->cascadeOnDelete();
            $table->decimal('quantity', 28, 10)->default(0);
            $table->decimal('avg_cost', 19, 6)->default(0);
            $table->timestamps();
            $table->unique(['portfolio_id','investment_asset_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_holdings');
    }
};

