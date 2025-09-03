<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('hostels')) {
            Schema::create('hostels', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->unsignedInteger('capacity')->default(0);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('rooms')) {
            Schema::create('rooms', function (Blueprint $table) {
                $table->id();
                $table->foreignId('hostel_id')->constrained('hostels')->cascadeOnDelete();
                $table->string('room_number');
                $table->unsignedInteger('capacity')->default(1);
                $table->timestamps();
                $table->unique(['hostel_id','room_number']);
            });
        }

        if (! Schema::hasTable('accommodation_assignments')) {
            Schema::create('accommodation_assignments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('student_id');
                $table->foreignId('room_id')->constrained('rooms')->cascadeOnDelete();
                $table->string('term')->nullable();
                $table->unsignedSmallInteger('year')->nullable();
                $table->date('check_in')->nullable();
                $table->date('check_out')->nullable();
                $table->timestamps();
                $table->index(['student_id','term','year']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('accommodation_assignments');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('hostels');
    }
};


