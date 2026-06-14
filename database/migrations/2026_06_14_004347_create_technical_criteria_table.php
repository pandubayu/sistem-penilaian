<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('technical_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('division_id')->constrained('divisions')->cascadeOnDelete();
            $table->string('aspect_name');
            $table->text('indicator_1')->comment('Tidak Memuaskan');
            $table->text('indicator_2')->comment('Perlu Peningkatan');
            $table->text('indicator_3')->comment('Cukup Memuaskan');
            $table->text('indicator_4')->comment('Sesuai Harapan');
            $table->integer('order_number')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('technical_criteria');
    }
};
