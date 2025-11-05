<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Table untuk menyimpan statistik author untuk performance
        Schema::create('author_statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->unique()->constrained()->onDelete('cascade');
            $table->unsignedInteger('total_voters')->default(0);
            $table->unsignedInteger('voters_above_5')->default(0); // untuk popularity ranking
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->decimal('last_30_days_avg', 3, 2)->default(0);
            $table->decimal('previous_30_days_avg', 3, 2)->default(0);
            $table->decimal('trending_score', 10, 2)->default(0);
            $table->foreignId('best_rated_book_id')->nullable()->constrained('books')->onDelete('set null');
            $table->foreignId('worst_rated_book_id')->nullable()->constrained('books')->onDelete('set null');
            $table->timestamp('last_calculated_at')->nullable();
            $table->timestamps();
            
            // Indexes for ranking queries
            $table->index('voters_above_5');
            $table->index('average_rating');
            $table->index('trending_score');
            $table->index('total_voters');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('author_statistics');
    }
};