<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table untuk menyimpan statistik pre-calculated untuk performance
        Schema::create('book_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->unique()->constrained()->onDelete('cascade');
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->unsignedInteger('total_voters')->default(0);
            $table->decimal('last_7_days_avg', 3, 2)->default(0);
            $table->decimal('last_30_days_avg', 3, 2)->default(0);
            $table->decimal('previous_30_days_avg', 3, 2)->default(0);
            $table->timestamp('last_calculated_at')->nullable();
            $table->timestamps();
            
            // Indexes for sorting and filtering
            $table->index('average_rating');
            $table->index('total_voters');
            $table->index('last_30_days_avg');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('book_statistics');
    }
};