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
        Schema::create('saw_results', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('assessment_period_id');
            $table->uuid('employee_id');
            $table->json('normalized_scores'); // Data normalisasi per kriteria
            $table->json('weighted_scores'); // Data nilai berbobot per kriteria
            $table->decimal('final_score', 8, 4); // Hasil akhir SAW
            $table->integer('rank'); // Peringkat
            $table->json('calculation_details')->nullable(); // Detail perhitungan SAW
            $table->timestamp('calculated_at');
            $table->timestamps();

            // Foreign keys
            $table->foreign('assessment_period_id')->references('id')->on('assessment_periods')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');

            // Indexes
            $table->unique(['assessment_period_id', 'employee_id'], 'unique_saw_result');
            $table->index(['assessment_period_id', 'rank'], 'idx_period_rank');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saw_results');
    }
};
