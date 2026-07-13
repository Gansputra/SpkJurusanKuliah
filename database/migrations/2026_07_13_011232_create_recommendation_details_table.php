<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recommendation_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recommendation_id')->constrained('recommendations')->onDelete('cascade');
            $table->foreignId('alternative_id')->constrained('alternatives')->onDelete('cascade');
            $table->decimal('nilai_preferensi', 15, 8)->default(0);
            $table->decimal('d_plus', 15, 8)->default(0);
            $table->decimal('d_minus', 15, 8)->default(0);
            $table->integer('ranking')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recommendation_details');
    }
};
