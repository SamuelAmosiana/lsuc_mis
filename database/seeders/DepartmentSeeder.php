<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            'Administration', 'Human Resource', 'Accounts', 'Academics', 'Library', 'IT Support'
        ];

        foreach ($departments as $name) {
            DB::table('department')->updateOrInsert(['name' => $name], []);
        }
    }
}


