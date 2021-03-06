<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::factory()->create([
            'name' => "admin",
            'email' => 'admin@example.com'
        ]);

        $educator = User::factory()->create([
            'name' => "educator",
            'email' => 'educator@example.com'
        ]);

        $student = User::factory()->create([
            'name' => "student",
            'email' => 'student@example.com'
        ]);

        $quiz = Quiz::factory()->create([
            'user_id' => $educator->id,
            'title' => 'Animal Quiz',
            'description' => 'Gaining knowledge about animal',
            'duration' => 3,
            'public' => true
        ]);

        Question::factory()->radio()->create([
            'user_id' => $educator->id,
            'quiz_id' => $quiz->id,
            'question_text' => 'What are rhino\'s horn made of?',
            'options' => array(
                ['option' => 'Bone'],
                ['option' => 'Ivory'],
                ['option' => 'Skin'],
                ['option' => 'Keratin'],
            ),
            'answer' => json_encode(['Keratin']),
            'answer_explanation' => '',
            'more_info_link' => '',
        ]);

        Question::factory()->radio()->create([
            'user_id' => $educator->id,
            'quiz_id' => $quiz->id,
            'question_text' => 'How many legs do butterflies have?',
            'options' => array(
                ['option' => 6],
                ['option' => 2],
                ['option' => 4],
                ['option' => 0],
            ),
            'answer' => json_encode([6]),
            'answer_explanation' => '',
            'more_info_link' => '',
        ]);

        Question::factory()->radio()->create([
            'user_id' => $educator->id,
            'quiz_id' => $quiz->id,
            'question_text' => 'A caterpillar has more muscles than humans do.',
            'options' => array(
                ['option' => 'true'],
                ['option' => 'false'],
            ),
            'answer' => json_encode(['true']),
            'answer_explanation' => '',
            'more_info_link' => '',
        ]);

        Question::factory()->radio()->create([
            'user_id' => $educator->id,
            'quiz_id' => $quiz->id,
            'question_text' => 'By definition, where does an abyssopelagic animal live?',
            'options' => array(
                ['option' => 'In the desert'],
                ['option' => 'On top of a mountain'],
                ['option' => 'At the bottom of the ocean'],
                ['option' => 'Inside a tree'],
            ),
            'answer' => json_encode(['At the bottom of the ocean']),
            'answer_explanation' => '',
            'more_info_link' => '',
        ]);

        $permissionByRole = [
            'admin' => [
                'create role',
                'delete role',
                'create permission',
                'delete permission',
                'assign role permission',
                'remove role permission',
                'assign user role',
                'remove user role',
                'view admin panel',
                'view api'
            ],
            'educator' => [
                'create quiz',
                'delete quiz',
                'update quiz',
                'create question',
                'delete question',
                'update question',
                'mark question',
                'view quiz',
                'view test result'
            ],
        ];

        foreach ($permissionByRole as $role => $permissions) {
            foreach ($permissions as $permission) {
                Permission::create(['name' => $permission]);
            }

            $role = Role::create(['name' => $role])
                ->givePermissionTo($permissions);
        }

        $admin->assignRole('admin');
        $educator->assignRole('educator');
    }
}