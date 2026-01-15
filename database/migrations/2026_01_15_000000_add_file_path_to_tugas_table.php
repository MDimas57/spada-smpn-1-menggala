<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('tugas')) {
            return;
        }

        if (!Schema::hasColumn('tugas', 'file_path')) {
            Schema::table('tugas', function (Blueprint $table) {
                $table->string('file_path')->nullable()->after('instruksi');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('tugas') && Schema::hasColumn('tugas', 'file_path')) {
            Schema::table('tugas', function (Blueprint $table) {
                $table->dropColumn('file_path');
            });
        }
    }
};
