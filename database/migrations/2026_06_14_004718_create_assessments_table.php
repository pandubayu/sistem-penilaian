<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mapping_id')->constrained('assessor_mappings')->cascadeOnDelete();
            $table->foreignId('period_id')->constrained('periods')->cascadeOnDelete();
            $table->foreignId('assessor_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->date('assessment_date');
            $table->decimal('total_score', 8, 2)->default(0);
            $table->decimal('average_score', 8, 2)->default(0);
            $table->enum('grade', ['A', 'B', 'C', 'D'])->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['mapping_id'], 'unique_assessment_per_mapping');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
