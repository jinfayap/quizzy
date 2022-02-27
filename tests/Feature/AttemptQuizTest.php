<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\TestAnswer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AttemptQuizTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function when_a_user_submits_the_answer_to_the_quiz_test_it_stores_the_answers()
    {
        $quiz = Quiz::factory()->create();

        $questionOne = Question::factory()->radio()->create([
            'quiz_id' => $quiz->id,
            'user_id' => $quiz->user_id,
            "options" => [
                ['option' => 'one'],
                ['option' => 'two'],
                ['option' => 'three'],
                ['option' => 'four'],
            ],
            "answer" => json_encode(['one'])
        ]);

        $questionTwo = Question::factory()->radio()->create([
            'quiz_id' => $quiz->id,
            'user_id' => $quiz->user_id,
            "options" => [
                ['option' => 'one'],
                ['option' => 'two'],
                ['option' => 'three'],
                ['option' => 'four'],
            ],
            "answer" => json_encode(['four'])
        ]);

        $this->post("/test/quiz/{$quiz->getRouteKey()}", ['answers' => [
            1 => 'one',
            2 => 'two',
        ]]);

        $this->assertDatabaseCount('tests', 1);
        $this->assertDatabaseCount('test_answers', 2);
    }

    /** @test */
    public function it_can_mark_as_correct_for_the_test_answer_if_it_is_a_radio_or_select_question()
    {
        $testUser = $this->signIn();

        $quiz = Quiz::factory()->create();


        $questionOne = Question::factory()->radio()->create([
            'quiz_id' => $quiz->id,
            'user_id' => $quiz->user_id,
            "options" => [
                ['option' => 'one'],
                ['option' => 'two'],
                ['option' => 'three'],
                ['option' => 'four'],
            ],
            "answer" => json_encode(['three'])
        ]);

        $questionTwo = Question::factory()->radio()->create([
            'quiz_id' => $quiz->id,
            'user_id' => $quiz->user_id,
            "options" => [
                ['option' => 'one'],
                ['option' => 'two'],
                ['option' => 'three'],
                ['option' => 'four'],
            ],
            "answer" => json_encode(['four'])
        ]);

        $this->post("/test/quiz/{$quiz->getRouteKey()}", ['answers' => [
            1 => 'one',
            2 => 'four',
        ]]);

        $testAnswers = TestAnswer::latest()->get();

        $this->assertFalse($testAnswers[0]->correct);
        $this->assertTrue($testAnswers[1]->correct);
    }

    /** @test */
    public function it_can_mark_as_correct_for_the_test_answer_if_it_is_a_checkbox_question()
    {
        $testUser = $this->signIn();

        $quiz = Quiz::factory()->create();


        $questionOne = Question::factory()->checkbox()->create([
            'quiz_id' => $quiz->id,
            'user_id' => $quiz->user_id,
            "options" => [
                ['option' => 'one'],
                ['option' => 'two'],
                ['option' => 'three'],
                ['option' => 'four'],
            ],
            "answer" => json_encode(['three', 'four'])
        ]);

        $this->post("/test/quiz/{$quiz->getRouteKey()}", ['answers' => [
            1 => ['three', 'four']
        ]]);

        $testAnswers = TestAnswer::latest()->get();

        $this->assertTrue($testAnswers[0]->correct);
    }

    /** @test */
    public function it_marked_as_incorrect_for_the_test_answer_if_one_of_its_option_is_wrong_for_checkbox_question()
    {
        $testUser = $this->signIn();

        $quiz = Quiz::factory()->create();


        $questionOne = Question::factory()->checkbox()->create([
            'quiz_id' => $quiz->id,
            'user_id' => $quiz->user_id,
            "options" => [
                ['option' => 'one'],
                ['option' => 'two'],
                ['option' => 'three'],
                ['option' => 'four'],
            ],
            "answer" => json_encode(['three', 'four'])
        ]);

        $this->post("/test/quiz/{$quiz->getRouteKey()}", ['answers' => [
            1 => ['three', 'one']
        ]]);

        $testAnswers = TestAnswer::latest()->get();

        $this->assertFalse($testAnswers[0]->correct);
    }

    /** @test */
    public function it_should_be_correct_for_the_checkbox_question_even_if_the_order_is_different()
    {
        $testUser = $this->signIn();

        $quiz = Quiz::factory()->create();


        $questionOne = Question::factory()->checkbox()->create([
            'quiz_id' => $quiz->id,
            'user_id' => $quiz->user_id,
            "options" => [
                ['option' => 'one'],
                ['option' => 'two'],
                ['option' => 'three'],
                ['option' => 'four'],
            ],
            "answer" => json_encode(['three', 'four'])
        ]);

        $this->post("/test/quiz/{$quiz->getRouteKey()}", ['answers' => [
            1 => ['four', 'three']
        ]]);

        $testAnswers = TestAnswer::latest()->get();

        $this->assertTrue($testAnswers[0]->correct);
    }

    /** @test */
    public function text_question_is_default_as_incorrect()
    {
        $testUser = $this->signIn();

        $quiz = Quiz::factory()->create();


        $questionOne = Question::factory()->create();
        $questionTwo = Question::factory()->textarea()->create();

        $this->post("/test/quiz/{$quiz->getRouteKey()}", ['answers' => [
            1 => 'some text answer',
            2 => 'some textarea answer',
        ]]);

        $testAnswers = TestAnswer::latest()->get();

        $this->assertFalse($testAnswers[0]->correct);
        $this->assertFalse($testAnswers[1]->correct);
    }
}