<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Create or find roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $managerRole = Role::firstOrCreate(['name' => 'manager']);
        $studentRole = Role::firstOrCreate(['name' => 'student']);
        $supervisorRole = Role::firstOrCreate(['name' => 'supervisor']); // Add Supervisor role

        // Define permissions for all roles
        $permissions = [
            'add-users',
            'view-students',
            'accept-student-requests',
            'reject-student-requests',
            'assign-quizzes',
            'view-quizzes',
            'view-quiz-results',
            'filter-students',
            'edit-student',   // New permission
            'delete-student'  // New permission
        ];

        // Create each permission if it does not already exist
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign all permissions to admin role
        $adminRole->givePermissionTo($permissions);

        // Define permissions for manager role
        $managerPermissions = [
            'view-students',
            'assign-quizzes',
            'filter-students',
        ];
        $managerRole->givePermissionTo($managerPermissions);

        // Define permissions for student role
        $studentPermissions = [
            'view-quizzes',
            'view-quiz-results',
        ];
        $studentRole->givePermissionTo($studentPermissions);


        // Define permissions for supervisor role
        $supervisorPermissions = [
            'view-students',   // View students
            'assign-quizzes',  // Assign quizzes
            'view-quizzes',    // View quizzes
            'filter-students', // Filter students
            'edit-student',    // Edit student
            'delete-student'   // Delete student
        ];
        $supervisorRole->givePermissionTo($supervisorPermissions); // Assign permissions to Supervisor








    }
}
