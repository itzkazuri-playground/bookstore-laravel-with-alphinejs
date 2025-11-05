<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('authors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->text('bio')->nullable();
            $table->string('country', 100)->nullable();
            $table->timestamps();
            
            // Indexes for optimization
            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('authors');
    }
};