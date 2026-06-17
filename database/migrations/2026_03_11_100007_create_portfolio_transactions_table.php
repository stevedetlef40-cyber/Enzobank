<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('portfolio_transactions')) {
            Schema::create('portfolio_transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('portfolio_id')->constrained('portfolios')->cascadeOnDelete();
                $table->foreignId('investment_asset_id')->constrained('investment_assets')->cascadeOnDelete();
                $table->enum('type', ['buy','sell','dividend','interest'])->default('buy');
                $table->decimal('quantity', 28, 10)->default(0);
                $table->decimal('price', 19, 6)->default(0);
                $table->decimal('fee', 19, 6)->default(0);
                $table->timestamp('executed_at')->nullable();
                $table->timestamps();
                $table->index(['portfolio_id','investment_asset_id','type']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolio_transactions');
    }
};
