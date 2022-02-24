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
        $user = User::factory()->create([
            'name' => "Jinfa",
            'email' => 'jinfayap@gmail.com'
        ]);

        $quiz = Quiz::factory()->create([
            'user_id' => $user->id,
            'title' => 'Animal Quiz',
            'description' => 'Gaining knowledge about animal',
            'duration' => 30
        ]);

        Question::factory()->radio()->create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'question_text' => 'What are rhino\'s horn made of?',
            'options' => array(
                [ 'option' => 'Bone' ],
                [ 'option' => 'Ivory' ],
                [ 'option' => 'Skin' ],
                [ 'option' => 'Keratin' ],
            ),
            'answer' => json_encode(['Keratin']),
            'answer_explanation' => '',
            'more_info_link' => '',
        ]);

        Question::factory()->radio()->create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'question_text' => 'How many legs do butterflies have?',
            'options' => array(
                [ 'option' => 6 ],
                [ 'option' => 2 ],
                [ 'option' => 4 ],
                [ 'option' => 0 ],
            ),
            'answer' => json_encode([6]),
            'answer_explanation' => '',
            'more_info_link' => '',
        ]);

        Question::factory()->radio()->create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'question_text' => 'A caterpillar has more muscles than humans do.',
            'options' => array(
                [ 'option' => 'true' ],
                [ 'option' => 'false' ],
            ),
            'answer' => json_encode(['true']),
            'answer_explanation' => '',
            'more_info_link' => '',
        ]);

        Question::factory()->radio()->create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'question_text' => 'By definition, where does an abyssopelagic animal live?',
            'options' => array(
                [ 'option' => 'In the desert' ],
                [ 'option' => 'On top of a mountain' ],
                [ 'option' => 'At the bottom of the ocean' ],
                [ 'option' => 'Inside a tree' ],
            ),
            'answer' => json_encode(['At the bottom of the ocean']),
            'answer_explanation' => '',
            'more_info_link' => '',
        ]);


        // Permission
        Permission::create(['name' => 'create role']);
        Permission::create(['name' => 'delete role']);

        Permission::create(['name' => 'create permission']);
        Permission::create(['name' => 'delete permission']);

        Permission::create(['name' => 'assign role permission']);
        Permission::create(['name' => 'remove role permission']);

        Permission::create(['name' => 'assign user role']);
        Permission::create(['name' => 'remove user role']);

        $role = Role::create(['name' => 'admin'])
            ->givePermissionTo(['create role', 'delete role', 'create permission', 'delete permission', 'assign role permission', 'remove role permission', 'assign user role', 'remove user role']);

        $user->assignRole($role);
    }
}