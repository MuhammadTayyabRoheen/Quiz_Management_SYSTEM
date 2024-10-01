<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        // Define permissions
        $permissions = [
            'add-users',
            'view-students',
            'accept-student-requests',
            'reject-student-requests',
            'assign-quizzes',
            'view-quizzes',
            'view-quiz-results',
            'filter-students', 
            'edit-student',  // New permission for editing a student
            'delete-student' // New permission for deleting a student// Added missing permission
        ];

        // Create each permission only if it does not exist
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
