<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 20)->unique();
            $table->string('name');
            $table->foreignId('division_id')->constrained('divisions')->restrictOnDelete();
            $table->tinyInteger('level')->default(1)->comment('1=Operator/Staff, 2=Ka.Bagian');
            $table->enum('contract_status', ['Tetap', 'Kontrak', 'Probation', 'Magang'])->default('Kontrak');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
