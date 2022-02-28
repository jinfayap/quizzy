<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\Test;
use App\Models\TestAnswer;
use App\Models\User;
use Facades\Tests\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageTestResultTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function tester_can_view_their_own_result()
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

        $tester = User::factory()->create();

        $this->actingAs($tester)->post("/test/quiz/{$quiz->getRouteKey()}", ['answers' => [
                1 => 'one',
                2 => 'four',
            ]]);

        $test = Test::first();

        $this->actingAs($tester)->get("/result/test/{$test->getRouteKey()}")->assertStatus(200);
    }

    /** @test */
    public function other_user_cannot_view_other_tester_result()
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

        $tester = User::factory()->create();

        $this->actingAs($tester)->post("/test/quiz/{$quiz->getRouteKey()}", ['answers' => [
                1 => 'one',
                2 => 'four',
            ]]);

        $test = Test::first();

        $anotherUser = User::factory()->create();

        $this->actingAs($anotherUser)->get("/result/test/{$test->getRouteKey()}")->assertStatus(403);
    }

    /** @test */
    public function user_with_view_test_result_permission_can_view_tester_result()
    {
        $educator = UserFactory::withRole('educator')
                ->withPermissions(['view test result'])
                ->create();

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

        $tester = User::factory()->create();

        $this->actingAs($tester)->post("/test/quiz/{$quiz->getRouteKey()}", ['answers' => [
                    1 => 'one',
                    2 => 'four',
                ]]);

        $test = Test::first();

        $this->actingAs($educator)->get("/result/test/{$test->getRouteKey()}")->assertStatus(200);
    }

    /** @test */
    public function user_with_mark_question_permission_can_mark_the_question_as_correct()
    {
        $educator = UserFactory::withRole('educator')
                ->withPermissions(['mark question'])
                ->create();

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

        $tester = User::factory()->create();

        $this->actingAs($tester)->post("/test/quiz/{$quiz->getRouteKey()}", ['answers' => [
                1 => 'one',
                2 => 'four',
            ]]);

        $testAnswers = TestAnswer::all();

        $this->assertFalse($testAnswers[0]->correct);
        $this->assertTrue($testAnswers[1]->correct);

        $this->actingAs($educator)->patch("/test-answer/{$testAnswers[0]->id}");

        $this->assertTrue($testAnswers[0]->fresh()->correct);
    }

    /** @test */
    public function user_without_mark_question_permission_cannot_mark_the_question_as_correct()
    {
        $educator = UserFactory::withRole('educator')
                ->withPermissions(['mark question'])
                ->create();

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

        $tester = User::factory()->create();

        $this->actingAs($tester)->post("/test/quiz/{$quiz->getRouteKey()}", ['answers' => [
                1 => 'one',
                2 => 'four',
            ]]);

        $testAnswers = TestAnswer::all();

        $this->assertFalse($testAnswers[0]->correct);
        $this->assertTrue($testAnswers[1]->correct);

        $this->actingAs($tester)->patch("/test-answer/{$testAnswers[0]->id}");

        $this->assertFalse($testAnswers[0]->fresh()->correct);
    }

    /** @test */
    public function user_with_mark_question_permission_can_mark_the_question_as_incorrect()
    {
        $educator = UserFactory::withRole('educator')
                    ->withPermissions(['mark question'])
                    ->create();

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

        $tester = User::factory()->create();

        $this->actingAs($tester)->post("/test/quiz/{$quiz->getRouteKey()}", ['answers' => [
                    1 => 'one',
                    2 => 'four',
                ]]);

        $testAnswers = TestAnswer::all();

        $this->assertFalse($testAnswers[0]->correct);
        $this->assertTrue($testAnswers[1]->correct);

        $this->actingAs($educator)->delete("/test-answer/{$testAnswers[1]->id}");

        $this->assertFalse($testAnswers[1]->fresh()->correct);
    }

    /** @test */
    public function user_without_mark_question_permission_cannot_mark_the_question_as_incorrect()
    {
        $educator = UserFactory::withRole('educator')
                ->withPermissions(['mark question'])
                ->create();

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

        $tester = User::factory()->create();

        $this->actingAs($tester)->post("/test/quiz/{$quiz->getRouteKey()}", ['answers' => [
                1 => 'one',
                2 => 'four',
            ]]);

        $testAnswers = TestAnswer::all();

        $this->assertFalse($testAnswers[0]->correct);
        $this->assertTrue($testAnswers[1]->correct);

        $this->actingAs($tester)->patch("/test-answer/{$testAnswers[1]->id}");

        $this->assertTrue($testAnswers[1]->fresh()->correct);
    }
}
