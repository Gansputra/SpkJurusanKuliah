<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('session_name')->nullable();
            $table->foreignId('ahp_result_id')->nullable()->constrained('ahp_results')->onDelete('set null');
            $table->json('topsis_steps')->nullable();
            $table->timestamp('calculated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recommendations');
    }
};
