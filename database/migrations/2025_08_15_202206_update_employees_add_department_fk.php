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
        Schema::table('employees', function (Blueprint $table) {
            // If the old 'department' column exists, drop it
            if (Schema::hasColumn('employees', 'department')) {
                $table->dropColumn('department');
            }

            // Add new foreign uuid column for department
            $table->foreignUuid('department_id')->nullable()->after('position')
                ->constrained('departments')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Drop foreign key and column
            if (Schema::hasColumn('employees', 'department_id')) {
                $table->dropConstrainedForeignId('department_id');
            }

            // Restore old text column
            $table->string('department')->nullable()->after('position');
        });
    }
};
