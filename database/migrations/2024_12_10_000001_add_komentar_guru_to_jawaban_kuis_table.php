<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('jawaban_kuis', function (Blueprint $table) {
            $table->integer('skor')->nullable()->change();
            $table->text('komentar_guru')->nullable()->after('skor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jawaban_kuis', function (Blueprint $table) {
            $table->dropColumn('komentar_guru');
        });
    }
};
