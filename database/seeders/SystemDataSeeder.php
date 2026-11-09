<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemDataSeeder extends Seeder
{
    public function run(): void
    {
        $userStatuses = ['Active', 'On Vacation', 'Inactive', 'Banned'];
        foreach ($userStatuses as $status) {
            DB::table('user_statuses')->updateOrInsert(['name' => $status]);
        }

        $roles = ['Owner', 'Administrator', 'Developer'];
        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(['name' => $role]);
        }

        $taskStatuses = ['Backlog', 'To Do', 'In Progress', 'Review', 'Done'];
        foreach ($taskStatuses as $status) {
            DB::table('task_statuses')->updateOrInsert(['name' => $status]);
        }

        $priorities = ['Low', 'Medium', 'High', 'Urgent'];
        foreach ($priorities as $priority) {
            DB::table('task_priorities')->updateOrInsert(['name' => $priority]);
        }
    }
}
