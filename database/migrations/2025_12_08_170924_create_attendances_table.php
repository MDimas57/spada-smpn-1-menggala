<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->index();
            $table->unsignedBigInteger('schedule_id')->index();
            $table->unsignedBigInteger('category_id')->index();
            $table->date('date')->index();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('siswas')->onDelete('cascade');
            $table->foreign('schedule_id')->references('id')->on('jadwal_pelajarans')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('attendance_categories')->onDelete('restrict');

            $table->unique(['student_id','schedule_id','date'],'att_unique_st_sched_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
