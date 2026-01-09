<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // Nama Course: "Matematika X IPA 1"
            $table->text('deskripsi')->nullable();
            $table->foreignId('mapel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('kelas_id')->constrained()->cascadeOnDelete();
            $table->foreignId('guru_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
