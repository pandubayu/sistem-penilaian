<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessor_mappings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('period_id')->constrained('periods')->cascadeOnDelete();
            $table->foreignId('assessor_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->enum('assessor_type', ['atasan', 'rekan'])->default('rekan');
            $table->boolean('is_done')->default(false);
            $table->timestamps();

            $table->unique(['period_id', 'assessor_id', 'employee_id'], 'unique_mapping');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessor_mappings');
    }
};
