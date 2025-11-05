<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->unsignedTinyInteger('rating'); // 1-10
            $table->string('voter_identifier', 255); // IP address or session ID for tracking 24h cooldown
            $table->timestamp('rated_at')->useCurrent();
            $table->timestamps();
            
            // Indexes for optimization
            $table->index('book_id');
            $table->index('rated_at');
            $table->index(['book_id', 'rated_at']);
            $table->index('voter_identifier');
            
            // Composite index for checking 24h cooldown per visitor
            $table->index(['voter_identifier', 'rated_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};