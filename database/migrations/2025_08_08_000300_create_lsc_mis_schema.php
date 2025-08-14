<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // STAFF & ROLES
        Schema::create('department', function (Blueprint $table) {
            $table->increments('department_id');
            $table->string('name', 100);
        });

        Schema::create('staff', function (Blueprint $table) {
            $table->increments('staff_id');
            $table->string('name', 150);
            $table->string('email', 150)->unique();
            $table->text('address')->nullable();
            $table->string('t_pin', 50)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('nrc', 50)->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->string('next_of_kin', 150)->nullable();
            $table->integer('department_id')->unsigned()->nullable();
            $table->foreign('department_id')->references('department_id')->on('department')->onDelete('set null');
        });

        Schema::create('role', function (Blueprint $table) {
            $table->increments('role_id');
            $table->string('role_name', 100);
            $table->text('role_description')->nullable();
        });

        Schema::create('staff_role', function (Blueprint $table) {
            $table->integer('staff_id')->unsigned();
            $table->integer('role_id')->unsigned();
            $table->primary(['staff_id', 'role_id']);
            $table->foreign('staff_id')->references('staff_id')->on('staff')->onDelete('cascade');
            $table->foreign('role_id')->references('role_id')->on('role')->onDelete('cascade');
        });

        // SCHOOLS, PROGRAMMES, COURSES
        Schema::create('school', function (Blueprint $table) {
            $table->increments('school_id');
            $table->string('name', 150);
        });

        Schema::create('programme', function (Blueprint $table) {
            $table->increments('programme_id');
            $table->string('name', 150);
            $table->integer('school_id')->unsigned()->nullable();
            $table->foreign('school_id')->references('school_id')->on('school')->onDelete('set null');
        });

        Schema::create('course', function (Blueprint $table) {
            $table->increments('course_id');
            $table->string('name', 150);
            $table->integer('programme_id')->unsigned()->nullable();
            $table->foreign('programme_id')->references('programme_id')->on('programme')->onDelete('set null');
        });

        // STUDENTS
        Schema::create('student', function (Blueprint $table) {
            $table->increments('student_id');
            $table->string('name', 150);
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('next_of_kin', 150)->nullable();
            $table->string('nrc', 50)->nullable();
            $table->text('address')->nullable();
            $table->integer('school_id')->unsigned()->nullable();
            $table->integer('programme_id')->unsigned()->nullable();
            $table->foreign('school_id')->references('school_id')->on('school')->onDelete('set null');
            $table->foreign('programme_id')->references('programme_id')->on('programme')->onDelete('set null');
        });

        // GRADES, FEES, SALARIES
        Schema::create('grade', function (Blueprint $table) {
            $table->increments('grade_id');
            $table->string('name', 50);
        });

        Schema::create('fee', function (Blueprint $table) {
            $table->increments('fee_id');
            $table->string('name', 100);
            $table->decimal('amount', 10, 2);
        });

        Schema::create('salary', function (Blueprint $table) {
            $table->increments('salary_id');
            $table->string('name', 100);
            $table->decimal('amount', 10, 2);
            $table->date('date_paid');
        });

        // RELATIONSHIPS
        Schema::create('staff_salary', function (Blueprint $table) {
            $table->integer('staff_id')->unsigned();
            $table->integer('salary_id')->unsigned();
            $table->primary(['staff_id', 'salary_id']);
            $table->foreign('staff_id')->references('staff_id')->on('staff')->onDelete('cascade');
            $table->foreign('salary_id')->references('salary_id')->on('salary')->onDelete('cascade');
        });

        Schema::create('staff_course', function (Blueprint $table) {
            $table->integer('staff_id')->unsigned();
            $table->integer('course_id')->unsigned();
            $table->primary(['staff_id', 'course_id']);
            $table->foreign('staff_id')->references('staff_id')->on('staff')->onDelete('cascade');
            $table->foreign('course_id')->references('course_id')->on('course')->onDelete('cascade');
        });

        Schema::create('student_course', function (Blueprint $table) {
            $table->integer('student_id')->unsigned();
            $table->integer('course_id')->unsigned();
            $table->primary(['student_id', 'course_id']);
            $table->foreign('student_id')->references('student_id')->on('student')->onDelete('cascade');
            $table->foreign('course_id')->references('course_id')->on('course')->onDelete('cascade');
        });

        Schema::create('student_grade', function (Blueprint $table) {
            $table->integer('student_id')->unsigned();
            $table->integer('grade_id')->unsigned();
            $table->primary(['student_id', 'grade_id']);
            $table->foreign('student_id')->references('student_id')->on('student')->onDelete('cascade');
            $table->foreign('grade_id')->references('grade_id')->on('grade')->onDelete('cascade');
        });

        Schema::create('student_fee', function (Blueprint $table) {
            $table->integer('student_id')->unsigned();
            $table->integer('fee_id')->unsigned();
            $table->primary(['student_id', 'fee_id']);
            $table->foreign('student_id')->references('student_id')->on('student')->onDelete('cascade');
            $table->foreign('fee_id')->references('fee_id')->on('fee')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_fee');
        Schema::dropIfExists('student_grade');
        Schema::dropIfExists('student_course');
        Schema::dropIfExists('staff_course');
        Schema::dropIfExists('staff_salary');
        Schema::dropIfExists('salary');
        Schema::dropIfExists('fee');
        Schema::dropIfExists('grade');
        Schema::dropIfExists('student');
        Schema::dropIfExists('course');
        Schema::dropIfExists('programme');
        Schema::dropIfExists('school');
        Schema::dropIfExists('staff_role');
        Schema::dropIfExists('role');
        Schema::dropIfExists('staff');
        Schema::dropIfExists('department');
    }
};


