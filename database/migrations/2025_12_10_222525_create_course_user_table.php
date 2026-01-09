<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_create_course_user_table.php

public function up()
{
    Schema::create('course_user_stars', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('course_id')->constrained()->onDelete('cascade');
        $table->timestamps();
        
        // Mencegah duplikasi (satu user hanya bisa star satu course sekali)
        $table->unique(['user_id', 'course_id']);
    });
}
};
