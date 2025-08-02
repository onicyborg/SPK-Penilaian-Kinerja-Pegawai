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
        Schema::create('employee_assessments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('assessment_period_id');
            $table->uuid('employee_id');
            $table->uuid('criteria_id');
            $table->integer('score'); // Range 1-5
            $table->text('notes')->nullable();
            $table->uuid('assessed_by');
            $table->timestamp('assessed_at');
            $table->timestamps();

            // Foreign keys
            $table->foreign('assessment_period_id')->references('id')->on('assessment_periods')->onDelete('cascade');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('criteria_id')->references('id')->on('criteria')->onDelete('cascade');
            $table->foreign('assessed_by')->references('id')->on('users')->onDelete('cascade');

            // Unique constraint
            $table->unique(['assessment_period_id', 'employee_id', 'criteria_id'], 'unique_assessment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_assessments');
    }
};
