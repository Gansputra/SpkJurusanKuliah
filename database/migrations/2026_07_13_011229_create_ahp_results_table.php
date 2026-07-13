<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ahp_results', function (Blueprint $table) {
            $table->id();
            $table->decimal('lambda_max', 15, 8)->default(0);
            $table->decimal('ci', 15, 8)->default(0);
            $table->decimal('cr', 15, 8)->default(0);
            $table->decimal('ri', 15, 8)->default(0);
            $table->boolean('is_consistent')->default(false);
            $table->json('weights')->nullable();
            $table->json('normalized_matrix')->nullable();
            $table->json('priority_vector')->nullable();
            $table->json('pairwise_matrix_snapshot')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ahp_results');
    }
};
