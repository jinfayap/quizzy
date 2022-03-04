<?php

namespace Tests\Feature\Question;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CheckboxTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_is_a_checkbox_question()
    {
        $user = $this->signIn();

        $quiz = Quiz::factory()->create(['user_id' => $user->id]);


        $question = Question::factory()->checkbox()->raw([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            ]);

        $question['answer'] = json_decode($question['answer']);

        $this->post("/quiz/{$quiz->getRouteKey()}/question", $question);

        $dbQuestion = Question::first();

        $this->assertEquals($question['quiz_id'], $dbQuestion->quiz_id);
        $this->assertEquals($question['user_id'], $dbQuestion->user_id);
        $this->assertEquals($question['question_text'], $dbQuestion->question_text);
        $this->assertEquals($question['question_type'], $dbQuestion->question_type);
        $this->assertEquals($question['answer_explanation'], $dbQuestion->answer_explanation);
        $this->assertEquals($question['more_info_link'], $dbQuestion->more_info_link);
        $this->assertEqualsCanonicalizing($question['options'], $dbQuestion->options);
        $this->assertEqualsCanonicalizing($question['answer'], $dbQuestion->answer);
    }

    /** @test */
    public function a_checkbox_question_can_be_edit()
    {
        $user = $this->signIn();

        $quiz = Quiz::factory()->create([
            'user_id' => $user->id
        ]);

        $question = Question::factory()->checkbox()->create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id
        ]);

        $this->patch("/quiz/{$quiz->getRouteKey()}/question/{$question->getRouteKey()}", $attributes = [
            'question_text' => 'question text',
            'question_type' => 'checkbox',
            'options' => [
                ['option' => 'one'],
                ['option' => 'two'],
                ['option' => 'three'],
                ['option' => 'four'],
            ],
            'answer' => ['one', 'two']
            ])->assertStatus(200);

        $dbQuestion = Question::first();

        $this->assertEquals($question['quiz_id'], $dbQuestion->quiz_id);
        $this->assertEquals($question['user_id'], $dbQuestion->user_id);
        $this->assertEquals($question['answer_explanation'], $dbQuestion->answer_explanation);
        $this->assertEquals($question['more_info_link'], $dbQuestion->more_info_link);

        $this->assertEquals($attributes['question_text'], $dbQuestion->question_text);
        $this->assertEquals($attributes['question_type'], $dbQuestion->question_type);
        $this->assertEqualsCanonicalizing($attributes['options'], $dbQuestion->options);
        $this->assertEqualsCanonicalizing($attributes['answer'], $dbQuestion->answer);
    }

    /** @test */
    public function checkbox_answer_requires_an_answer_that_is_found_in_the_options_when_creating()
    {
        $user = $this->signIn();

        $quiz = Quiz::factory()->create(['user_id' => $user->id]);

        $question = Question::factory()->checkbox()->raw([
            'quiz_id' => $quiz->id,
            'user_id' => $user->id,
            "options" => [
                ['option' => 'one'],
                ['option' => 'two'],
                ['option' => 'three'],
                ['option' => 'four'],
            ],
            "answer" => ["one", "five"]
        ]);

        $this->post("/quiz/{$quiz->getRouteKey()}/question", $question)->assertSessionHasErrors(['answer' => "The answer should contain values found in the options choices"]);

        $this->assertDatabaseCount('questions', 0);

        $question = Question::factory()->checkbox()->raw([
            'quiz_id' => $quiz->id,
            'user_id' => $user->id,
            "options" => [
                ['option' => 'one'],
                ['option' => 'two'],
                ['option' => 'three'],
                ['option' => 'four'],
            ],
            "answer" => ["one", "two"]
        ]);

        $this->post("/quiz/{$quiz->getRouteKey()}/question", $question)->assertStatus(200);

        $this->assertDatabaseCount('questions', 1);
    }

    /** @test */
    public function checkbox_answer_requires_an_answer_that_is_found_in_the_options_when_updating()
    {
        $user = $this->signIn();

        $quiz = Quiz::factory()->create(['user_id' => $user->id]);

        $question = Question::factory()->checkbox()->create([
            'quiz_id' => $quiz->id,
            'user_id' => $user->id,
            "options" => [
                ['option' => 'one'],
                ['option' => 'two'],
                ['option' => 'three'],
                ['option' => 'four'],
            ],
            "answer" => json_encode(['one'])
        ]);

        $this->patch("/quiz/{$quiz->getRouteKey()}/question/{$question->getRouteKey()}", [
            'question_text' => 'question text',
            'question_type' => 'checkbox',
            'options' => [
                ['option' => 'one'],
                ['option' => 'two'],
                ['option' => 'three'],
                ['option' => 'four'],
            ],
            'answer' => ['five', 'two'],
        ])->assertSessionHasErrors(['answer' => "The answer should contain values found in the options choices"]);

        $this->patch("/quiz/{$quiz->getRouteKey()}/question/{$question->getRouteKey()}", [
            'question_text' => 'question text',
            'question_type' => 'checkbox',
            'options' => [
                ['option' => 'one'],
                ['option' => 'two'],
                ['option' => 'three'],
                ['option' => 'four'],
            ],
            'answer' => ['one', 'two'],
        ])->assertStatus(200);
    }

    /** @test */
    public function checkbox_question_can_have_single_or_multiple_answer_when_creating()
    {
        $user = $this->signIn();

        $quiz = Quiz::factory()->create(['user_id' => $user->id]);

        $questionSingleChoice = Question::factory()->checkbox()->raw([
            'quiz_id' => $quiz->id,
            'user_id' => $user->id,
            "options" => [
                ['option' => 'one'],
                ['option' => 'two'],
                ['option' => 'three'],
                ['option' => 'four'],
            ],
            "answer" => ["one"]
        ]);

        $this->post("/quiz/{$quiz->getRouteKey()}/question", $questionSingleChoice)->assertStatus(200);

        $this->assertDatabaseCount('questions', 1);

        $questionMultipleChoice = Question::factory()->checkbox()->raw([
            'quiz_id' => $quiz->id,
            'user_id' => $user->id,
            "options" => [
                ['option' => 'one'],
                ['option' => 'two'],
                ['option' => 'three'],
                ['option' => 'four'],
            ],
            "answer" => ["one", "two"]
        ]);

        $this->post("/quiz/{$quiz->getRouteKey()}/question", $questionMultipleChoice)->assertStatus(200);

        $this->assertDatabaseCount('questions', 2);
    }

    /** @test */
    public function checkbox_question_can_have_single_or_multiple_answers_when_updating()
    {
        $user = $this->signIn();

        $quiz = Quiz::factory()->create(['user_id' => $user->id]);

        $question = Question::factory()->checkbox()->create([
            'quiz_id' => $quiz->id,
            'user_id' => $user->id,
            "options" => [
                ['option' => 'one'],
                ['option' => 'two'],
                ['option' => 'three'],
                ['option' => 'four'],
            ],
            "answer" => json_encode(['one'])
        ]);

        $this->patch("/quiz/{$quiz->getRouteKey()}/question/{$question->getRouteKey()}", [
            'question_text' => 'question text',
            'question_type' => 'checkbox',
            'options' => [
                ['option' => 'one'],
                ['option' => 'two'],
                ['option' => 'three'],
                ['option' => 'four'],
            ],
            'answer' => ["one"],
        ])->assertStatus(200);

        $this->patch("/quiz/{$quiz->getRouteKey()}/question/{$question->getRouteKey()}", [
            'question_text' => 'question text',
            'question_type' => 'checkbox',
            'options' => [
                ['option' => 'one'],
                ['option' => 'two'],
                ['option' => 'three'],
                ['option' => 'four'],
            ],
            'answer' => ["one", "two"],
        ])->assertStatus(200);
    }
}
