<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('holidays')) {
            Schema::create('holidays', function (Blueprint $table) {
                $table->id();
                $table->date('holiday_date')->unique();
                $table->string('name')->nullable();
                $table->string('region')->nullable();
                $table->timestamps();
                $table->index(['holiday_date', 'region']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};

