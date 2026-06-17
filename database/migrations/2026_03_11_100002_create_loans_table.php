<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('loan_product_id')->nullable()->constrained('loan_products')->nullOnDelete();
            $table->decimal('principal', 19, 4);
            $table->decimal('interest_rate', 8, 4); // lock at creation
            $table->unsignedInteger('term_months');
            $table->date('start_date')->nullable();
            $table->decimal('balance_principal', 19, 4)->default(0);
            $table->enum('status', ['pending','active','closed','defaulted'])->default('pending');
            $table->date('next_due_date')->nullable();
            $table->timestamps();
            $table->index(['user_id','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};

