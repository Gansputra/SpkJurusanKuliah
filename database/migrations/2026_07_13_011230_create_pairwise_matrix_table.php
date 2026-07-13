<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pairwise_matrix', function (Blueprint $table) {
            $table->id();
            $table->foreignId('criteria1_id')->constrained('criteria')->onDelete('cascade');
            $table->foreignId('criteria2_id')->constrained('criteria')->onDelete('cascade');
            $table->decimal('nilai', 10, 6)->default(1);
            $table->timestamps();

            $table->unique(['criteria1_id', 'criteria2_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pairwise_matrix');
    }
};
