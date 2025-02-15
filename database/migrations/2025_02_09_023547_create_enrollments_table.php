<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')
                ->constrained()
                ->onDelete('cascade');
                
            $table->foreignId('student_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->timestamps();

            $table->unique(['student_id', 'schedule_id'])->name('enrollments_student_id_schedule_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
