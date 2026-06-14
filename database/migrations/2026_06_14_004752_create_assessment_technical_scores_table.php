<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessment_technical_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('assessments')->cascadeOnDelete();
            $table->foreignId('criteria_id')->constrained('technical_criteria')->cascadeOnDelete();
            $table->tinyInteger('score')->comment('1-4');
            $table->timestamps();

            $table->unique(['assessment_id', 'criteria_id'], 'unique_tech_score');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_technical_scores');
    }
};
