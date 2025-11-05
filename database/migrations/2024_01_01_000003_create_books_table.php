<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title', 500);
            $table->foreignId('author_id')->constrained()->onDelete('cascade');
            $table->string('isbn', 20)->unique();
            $table->string('publisher', 255)->nullable();
            $table->year('publication_year');
            $table->text('description')->nullable();
            $table->enum('availability_status', ['available', 'rented', 'reserved'])->default('available');
            $table->string('store_location', 100)->nullable();
            $table->timestamps();
            
            // Indexes for optimization and filtering
            $table->index('title');
            $table->index('author_id');
            $table->index('publication_year');
            $table->index('availability_status');
            $table->index('store_location');
            $table->index(['author_id', 'availability_status']);
            $table->index(['publication_year', 'availability_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};