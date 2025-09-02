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
        Schema::create('staff_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('nrc')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->default('Other');
            $table->string('next_of_kin')->nullable();
            $table->unsignedInteger('department_id')->nullable();
            $table->foreign('department_id')->references('department_id')->on('department')->onDelete('set null');
            $table->string('position')->nullable();
            $table->date('employment_date')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index('user_id');
            $table->index('email');
            $table->index('department_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_profiles');
    }
};
