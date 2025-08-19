<?php

namespace Database\Seeders;

use App\Models\Student;
use App\Models\StatusNote;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusNotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Student::all();
        $statusNotes = [];
        $statuses = ['active', 'inactive', 'suspended', 'graduated'];
        $reasons = [
            'active' => ['New admission', 'Returning from leave', 'Status updated by admin', 'Reinstated after probation'],
            'inactive' => ['Academic leave', 'Medical leave', 'Personal reasons', 'Financial issues'],
            'suspended' => ['Academic probation', 'Disciplinary action', 'Non-payment of fees', 'Code of conduct violation'],
            'graduated' => ['Completed program requirements', 'Awarded degree', 'Graduation ceremony attended']
        ];
        
        foreach ($students as $student) {
            $statusHistory = [];
            $currentDate = Carbon::now();
            
            // Generate 1-3 status changes per student
            $numChanges = rand(1, 3);
            
            for ($i = 0; $i < $numChanges; $i++) {
                $status = $statuses[array_rand($statuses)];
                $reason = $reasons[$status][array_rand($reasons[$status])];
                $note = $this->generateStatusNote($status, $reason);
                
                $statusHistory[] = [
                    'student_id' => $student->id,
                    'status' => $status,
                    'note' => $note,
                    'created_by' => 1, // HR Manager
                    'created_at' => $currentDate->copy()->subMonths(rand(1, 12 * 3)),
                    'updated_at' => Carbon::now(),
                ];
                
                // Update the current date for the next status change
                $currentDate->addDays(rand(30, 180));
            }
            
            // Add the current status as the most recent note
            $currentStatus = $student->status;
            $reason = $reasons[$currentStatus][array_rand($reasons[$currentStatus])];
            $note = $this->generateStatusNote($currentStatus, $reason);
            
            $statusHistory[] = [
                'student_id' => $student->id,
                'status' => $currentStatus,
                'note' => $note,
                'created_by' => 1, // HR Manager
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            
            $statusNotes = array_merge($statusNotes, $statusHistory);
        }
        
        // Insert all status notes at once for better performance
        StatusNote::insert($statusNotes);
    }
    
    /**
     * Generate a status note based on status and reason.
     *
     * @param string $status
     * @param string $reason
     * @return string
     */
    private function generateStatusNote(string $status, string $reason): string
    {
        $notes = [
            'active' => [
                'Student has been marked as active. Reason: ' . $reason,
                'Status updated to active. Details: ' . $reason,
                'Student account activated. Note: ' . $reason,
            ],
            'inactive' => [
                'Student marked as inactive. Reason: ' . $reason,
                'Account deactivated. Details: ' . $reason,
                'Status changed to inactive. Note: ' . $reason,
            ],
            'suspended' => [
                'Student suspended from the institution. Reason: ' . $reason,
                'Suspension applied. Details: ' . $reason,
                'Account suspended. Note: ' . $reason,
            ],
            'graduated' => [
                'Student has successfully graduated. Details: ' . $reason,
                'Graduation status confirmed. Note: ' . $reason,
                'Program completed. ' . $reason,
            ],
        ];
        
        $possibleNotes = $notes[$status] ?? ['Status updated to ' . $status . '. Note: ' . $reason];
        return $possibleNotes[array_rand($possibleNotes)];
    }
}
