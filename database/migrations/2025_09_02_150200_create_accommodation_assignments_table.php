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
        // First create the table without foreign keys
        Schema::create('accommodation_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('room_id');
            $table->string('term');
            $table->year('year');
            $table->date('check_in');
            $table->date('check_out')->nullable();
            $table->timestamps();
            
            // Add index for frequently queried columns
            $table->index(['student_id', 'term', 'year']);
        });

        // Then add the foreign key constraints
        Schema::table('accommodation_assignments', function (Blueprint $table) {
            $table->foreign('student_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
                  
            $table->foreign('room_id')
                  ->references('id')
                  ->on('rooms')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accommodation_assignments');
    }
};
