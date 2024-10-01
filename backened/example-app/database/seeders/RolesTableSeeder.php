<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // Get roles
        $adminRole = Role::where('name', 'admin')->first();
        $managerRole = Role::where('name', 'manager')->first();
        $studentRole = Role::where('name', 'student')->first();
        $supervisorRole = Role::firstOrCreate(['name' => 'supervisor']); // Add Supervisor role

        // Define permissions for each role

        // Admin has all permissions
        $adminPermissions = [
            'add-users',
            'view-students',
            'accept-student-requests',
            'reject-student-requests',
            'assign-quizzes',
            'view-quizzes',
            'view-quiz-results',
        ];

        // Manager permissions (a subset of admin)
        $managerPermissions = [
            'view-students',
            'assign-quizzes',
        ];

        // Student permissions (minimal permissions)
        $studentPermissions = [
            'view-quizzes',
            'view-quiz-results',
        ];

        // Assign permissions to admin
        foreach ($adminPermissions as $permission) {
            $permissionInstance = Permission::where('name', $permission)->first();
            $adminRole->givePermissionTo($permissionInstance);
        }

        // Assign permissions to manager
        foreach ($managerPermissions as $permission) {
            $permissionInstance = Permission::where('name', $permission)->first();
            $managerRole->givePermissionTo($permissionInstance);
        }

        // Assign permissions to student
        foreach ($studentPermissions as $permission) {
            $permissionInstance = Permission::where('name', $permission)->first();
            $studentRole->givePermissionTo($permissionInstance);
        }
    }
}
