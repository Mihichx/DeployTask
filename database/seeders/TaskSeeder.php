<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $activeStatusId = DB::table('user_statuses')->where('name', 'Active')->value('id');
        $roles = DB::table('roles')->pluck('id', 'name');

        // 1. Создаем тестовых пользователей, если таблица пуста
        if (User::count() === 0) {
            $usersData = [
                [
                    'login' => 'owner_ivan',
                    'name' => 'Иван Владельцев',
                    'email' => 'owner@example.com',
                ],
                [
                    'login' => 'admin_anna',
                    'name' => 'Анна Админова',
                    'email' => 'admin@example.com',
                ],
                [
                    'login' => 'dev_alex',
                    'name' => 'Алексей Разрабов',
                    'email' => 'dev1@example.com',
                ],
                [
                    'login' => 'dev_maria',
                    'name' => 'Мария Кодова',
                    'email' => 'dev2@example.com',
                ],
            ];

            foreach ($usersData as $userData) {
                User::create([
                    'login' => $userData['login'],
                    'name' => $userData['name'],
                    'email' => $userData['email'],
                    'password' => Hash::make('password'),
                    'user_status_id' => $activeStatusId,
                ]);
            }
        }

        $users = User::all();

        // 2. Создаем тестовые команды и привязываем к ним участников с ролями
        if (Team::count() === 0) {
            $teams = ['Backend Разработка', 'Frontend Команда', 'Дизайн и Продукт'];

            foreach ($teams as $teamName) {
                $teamAttributes = ['name' => $teamName];

                if (DB::getSchemaBuilder()->hasColumn('teams', 'owner_id')) {
                    $teamAttributes['owner_id'] = $users->first()->id;
                } elseif (DB::getSchemaBuilder()->hasColumn('teams', 'created_by')) {
                    $teamAttributes['created_by'] = $users->first()->id;
                }

                $team = Team::create($teamAttributes);

                foreach ($users as $index => $user) {
                    $roleName = match ($index) {
                        0 => 'Owner',
                        1 => 'Administrator',
                        default => 'Developer',
                    };

                    $user->teams()->attach($team->id, [
                        'role_id' => $roles[$roleName] ?? $roles->first(),
                    ]);
                }
            }
        }

        // 3. Собираем ID всех справочников для задач
        $userIds = $users->pluck('id');
        $teamIds = Team::pluck('id');
        $statusIds = DB::table('task_statuses')->pluck('id', 'name');
        $priorityIds = DB::table('task_priorities')->pluck('id');

        $fibonacciPoints = array(1, 2, 3, 5, 8, 13);
        $subtaskPoints = array(1, 2, 3);
        $doneStatusId = $statusIds['Done'] ?? $statusIds->last();

        // 4. Создаем 25 основных задач
        $parentTasks = collect();

        for ($i = 0; $i < 25; $i++) {
            $statusId = $statusIds->isNotEmpty() ? $statusIds->random() : null;
            $isDone = $statusId && $statusId === $doneStatusId;

            $task = Task::create([
                'name' => fake('ru_RU')->realText(45),
                'description' => fake('ru_RU')->boolean(80) ? fake('ru_RU')->realText(200) : null,
                'story_points' => fake()->boolean(85) ? fake()->randomElement($fibonacciPoints) : null,
                'deadline' => fake()->boolean(70) ? fake()->dateTimeBetween('-5 days', '+1 month') : null,
                'created_by' => $userIds->random(),
                'parent_id' => null,
                'team_id' => $teamIds->random(),
                'task_priority_id' => $priorityIds->isNotEmpty() ? $priorityIds->random() : null,
                'task_status_id' => $statusId,
                'closed_at' => $isDone ? fake()->dateTimeBetween('-10 days', 'now') : null,
            ]);

            // Назначаем 1 или 2 случайных исполнителей на задачу
            $task->assignees()->attach(
                $userIds->random(rand(1, 2))->all()
            );

            $parentTasks->push($task);
        }

        // 5. Создаем 15 подзадач
        for ($i = 0; $i < 15; $i++) {
            $parent = $parentTasks->random();
            $statusId = $statusIds->isNotEmpty() ? $statusIds->random() : null;
            $isDone = $statusId && $statusId === $doneStatusId;

            $subtask = Task::create([
                'name' => 'Подзадача: ' . fake('ru_RU')->realText(35),
                'description' => fake('ru_RU')->boolean(50) ? fake('ru_RU')->realText(120) : null,
                'story_points' => fake()->randomElement($subtaskPoints),
                'deadline' => $parent->deadline,
                'created_by' => $userIds->random(),
                'parent_id' => $parent->id,
                'team_id' => $parent->team_id,
                'task_priority_id' => $priorityIds->isNotEmpty() ? $priorityIds->random() : null,
                'task_status_id' => $statusId,
                'closed_at' => $isDone ? fake()->dateTimeBetween('-5 days', 'now') : null,
            ]);

            // Назначаем 1 случайного исполнителя на подзадачу
            $subtask->assignees()->attach(
                $userIds->random(1)->all()
            );
        }
    }
}
