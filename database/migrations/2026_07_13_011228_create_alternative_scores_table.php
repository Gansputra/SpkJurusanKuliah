<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alternative_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alternative_id')->constrained('alternatives')->onDelete('cascade');
            $table->foreignId('criteria_id')->constrained('criteria')->onDelete('cascade');
            $table->decimal('nilai', 10, 4)->default(0);
            $table->timestamps();

            $table->unique(['alternative_id', 'criteria_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alternative_scores');
    }
};
