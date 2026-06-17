<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loan_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained('loans')->cascadeOnDelete();
            $table->date('due_date');
            $table->decimal('amount_due', 19, 4);
            $table->decimal('amount_paid', 19, 4)->default(0);
            $table->enum('status', ['due','paid','late'])->default('due');
            $table->timestamps();
            $table->index(['loan_id','status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loan_payments');
    }
};

