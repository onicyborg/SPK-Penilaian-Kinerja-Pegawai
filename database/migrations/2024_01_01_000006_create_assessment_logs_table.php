<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assessment_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('assessment_period_id');
            $table->string('action', 100); // 'created', 'updated', 'calculated', 'completed'
            $table->text('description');
            $table->uuid('user_id');
            $table->timestamps();

            // Foreign keys
            $table->foreign('assessment_period_id')->references('id')->on('assessment_periods')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_logs');
    }
};
