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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Use unsignedBigInteger instead of foreignId to avoid issues with migrations order
            $table->unsignedBigInteger('program_id')->nullable();
            
            $table->string('student_id')->unique();
            $table->enum('status', ['active', 'inactive', 'suspended', 'graduated'])->default('active');
            $table->string('batch_year')->nullable();
            $table->string('current_semester')->nullable();
            $table->date('admission_date')->nullable();
            $table->string('phone')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_relation')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->text('address')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('blood_group', 5)->nullable();
            $table->string('nationality')->nullable();
            $table->string('religion')->nullable();
            $table->string('passport_number')->nullable();
            $table->string('id_card_number')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->text('notes')->nullable();
            
            // Use unsignedBigInteger for status_updated_by as well
            $table->unsignedBigInteger('status_updated_by')->nullable();
            
            $table->timestamp('status_updated_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        
        // Add foreign key constraints after all tables are created
        Schema::table('students', function (Blueprint $table) {
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('set null');
            $table->foreign('status_updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
