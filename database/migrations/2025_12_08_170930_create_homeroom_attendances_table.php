<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('homeroom_attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->index();
            $table->unsignedBigInteger('class_id')->index();
            $table->unsignedBigInteger('category_id')->index();
            $table->date('date')->index();
            $table->enum('check_type',['masuk','pulang']);
            $table->time('time')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('siswas')->onDelete('cascade');
            $table->foreign('class_id')->references('id')->on('kelas')->onDelete('cascade');
            $table->foreign('category_id')->references('id')->on('attendance_categories')->onDelete('restrict');

            $table->unique(['student_id','date','check_type'],'homeroom_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('homeroom_attendances');
    }
};
