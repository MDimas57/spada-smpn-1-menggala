<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('attendance_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('name');
            $table->string('color')->nullable();
            $table->timestamps();
        });

        DB::table('attendance_categories')->insert([
            ['code'=>'H','name'=>'Hadir','color'=>'green','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'S','name'=>'Sakit','color'=>'yellow','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'I','name'=>'Izin','color'=>'blue','created_at'=>now(),'updated_at'=>now()],
            ['code'=>'A','name'=>'Alpa','color'=>'red','created_at'=>now(),'updated_at'=>now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_categories');
    }
};
