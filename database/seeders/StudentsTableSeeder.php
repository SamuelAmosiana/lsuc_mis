<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all student users who don't already have a student record
        $users = User::where('role', 'student')
            ->whereNotIn('id', function($query) {
                $query->select('user_id')->from('students');
            })
            ->get();

        if ($users->isEmpty()) {
            $this->command->info('No users with student role found or all users already have student records.');
            return;
        }

        $programs = \App\Models\Program::all();
        
        if ($programs->isEmpty()) {
            $this->command->error('No programs found. Please seed programs first.');
            return;
        }

        $statuses = ['active', 'inactive', 'suspended', 'graduated'];
        $genders = ['male', 'female', 'other'];
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        
        $studentData = [];
        
        foreach ($users as $index => $user) {
            $program = $programs->random();
            $status = $statuses[array_rand($statuses)];
            $gender = $genders[array_rand($genders)];
            $bloodGroup = $bloodGroups[array_rand($bloodGroups)];
            $batchYear = 2020 + ($index % 4); // 2020-2023
            
            // Generate a unique student ID
            $studentId = 'STU' . str_pad($index + 1, 5, '0', STR_PAD_LEFT);
            
            // Ensure the student ID is unique
            while (\App\Models\Student::where('student_id', $studentId)->exists()) {
                $index++;
                $studentId = 'STU' . str_pad($index + 1, 5, '0', STR_PAD_LEFT);
            }
            
            $studentData[] = [
                'user_id' => $user->id,
                'program_id' => $program->id,
                'student_id' => $studentId,
                'status' => $status,
                'batch_year' => $batchYear,
                'current_semester' => rand(1, 8),
                'admission_date' => Carbon::now()->subYears(rand(1, 4))->subMonths(rand(1, 12)),
                'phone' => '25' . str_pad(rand(0, 9999999), 7, '0'),
                'emergency_contact_name' => 'Emergency Contact ' . $user->name,
                'emergency_contact_relation' => ['Father', 'Mother', 'Guardian'][rand(0, 2)],
                'emergency_contact_phone' => '88' . str_pad(rand(0, 9999999), 7, '0'),
                'address' => '123 University St, City, Country',
                'date_of_birth' => Carbon::now()->subYears(rand(18, 25))->subMonths(rand(1, 12)),
                'gender' => $gender,
                'blood_group' => $bloodGroup,
                'nationality' => 'Lesotho',
                'religion' => ['Christian', 'Muslim', 'Hindu', 'Other'][rand(0, 3)],
                'passport_number' => 'P' . strtoupper(substr(uniqid(), -8)),
                'id_card_number' => 'ID' . str_pad(rand(0, 999999), 6, '0'),
                'bank_account_number' => str_pad(rand(0, 9999999999), 10, '0'),
                'bank_name' => ['Standard Bank', 'Nedbank', 'First National Bank', 'Lesotho PostBank'][rand(0, 3)],
                'notes' => $status === 'suspended' ? 'Student on academic probation' : null,
                'status_updated_by' => 1, // HR Manager
                'status_updated_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        // Insert all students at once for better performance
        Student::insert($studentData);
    }
}
