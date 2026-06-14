<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grading_thresholds', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('employee_level')->comment('1=Operator/Staff, 2=Ka.Bagian');
            $table->enum('grade', ['A', 'B', 'C', 'D']);
            $table->integer('min_score');
            $table->integer('max_score')->nullable()->comment('null berarti tidak ada batas atas (grade A)');
            $table->text('reward_text');
            $table->text('punishment_text');
            $table->timestamps();

            $table->unique(['employee_level', 'grade']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grading_thresholds');
    }
};
