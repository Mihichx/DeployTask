<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemDataSeeder extends Seeder
{
    public function run(): void
    {
        // Статусы пользователей
        $userStatuses = array(
            'Active' => '#10B981',      // Изумрудный (активен)
            'On Vacation' => '#3B82F6', // Синий (в отпуске)
            'Inactive' => '#71717A',    // Серый (неактивен)
            'Banned' => '#EF4444',      // Красный (заблокирован)
        );
        foreach ($userStatuses as $name => $color) {
            DB::table('user_statuses')->updateOrInsert(
                array('name' => $name),
                array('color' => $color)
            );
        }

        // Роли в команде (в фирменных цветах проекта)
        $roles = array(
            'Owner' => '#EF4444',         // Красный (главный акцент)
            'Administrator' => '#F97316', // Оранжевый
            'Developer' => '#3B82F6',     // Синий
        );
        foreach ($roles as $name => $color) {
            DB::table('roles')->updateOrInsert(
                array('name' => $name),
                array('color' => $color)
            );
        }

        // Статусы задач (для канбан-колонок)
        $taskStatuses = array(
            'Backlog' => '#71717A',     // Серый
            'To Do' => '#3B82F6',       // Синий
            'In Progress' => '#F59E0B', // Янтарный
            'Review' => '#8B5CF6',      // Фиолетовый
            'Done' => '#10B981',        // Зеленый
        );
        foreach ($taskStatuses as $name => $color) {
            DB::table('task_statuses')->updateOrInsert(
                array('name' => $name),
                array('color' => $color)
            );
        }

        // Приоритеты задач
        $priorities = array(
            'Low' => '#71717A',    // Серый
            'Medium' => '#3B82F6', // Синий
            'High' => '#F97316',   // Оранжевый
            'Urgent' => '#EF4444', // Красный (срочный)
        );
        foreach ($priorities as $name => $color) {
            DB::table('task_priorities')->updateOrInsert(
                array('name' => $name),
                array('color' => $color)
            );
        }
    }
}
