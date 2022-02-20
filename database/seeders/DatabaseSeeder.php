<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Seeder;

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
    }
}