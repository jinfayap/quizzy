<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\Test;
use App\Models\TestAnswer;
use App\Models\User;
use Facades\Tests\Factories\QuizFactory;
use Facades\Tests\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ManageTestResultTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function tester_can_view_their_result()
    {

        $quiz = QuizFactory::withQuestions(2)->create();

        $tester = $this->signIn();

        $this->post("/test/quiz/{$quiz->getRouteKey()}", ['answers' => [
            1 => 'one',
            2 => 'four',
        ]]);

        $test = Test::first();

        $this->get(route('result.show', $test))->assertStatus(200);
    }

    /** @test */
    public function tester_can_view_their_result_in_public_url()
    {

        $quiz = QuizFactory::withQuestions(2)->create([
            'public' => true
        ]);

        $tester = $this->signIn();

        $this->post("/test/quiz/{$quiz->getRouteKey()}", ['answers' => [
            1 => 'one',
            2 => 'four',
        ]]);

        $test = Test::first();

        $this->get(route('result.show', $test))->assertStatus(200);
        $this->get(route('result.public', $test))->assertStatus(200);
    }

    /** @test */
    public function other_user_cannot_view_other_tester_result()
    {
        $quiz = QuizFactory::withQuestions(2)->create();

        $test = Test::factory()->create([
            'quiz_id' => $quiz->id
        ]);

        $this->assertFalse($quiz->public);

        $anotherUser = $this->signIn();

        $this->get(route('result.show', $test))->assertStatus(403);
        $this->get(route('result.public', $test))->assertStatus(403);

        $quiz->update([
            'public' => true
        ]);

        $this->assertTrue($quiz->public);

        $this->get(route('result.show', $test))->assertStatus(403);
        $this->get(route('result.public', $test))->assertStatus(403);
    }

    /** @test */
    public function guest_cannot_view_other_tester_result()
    {
        $quiz = QuizFactory::withQuestions(2)->create();

        $test = Test::factory()->create([
            'quiz_id' => $quiz->id
        ]);

        $this->assertFalse($quiz->public);

        $this->get(route('result.show', $test))->assertStatus(403);
        $this->get(route('result.public', $test))->assertStatus(403);

        $quiz->update([
            'public' => true
        ]);

        $this->assertTrue($quiz->public);

        $this->get(route('result.show', $test))->assertStatus(403);
        $this->get(route('result.public', $test))->assertStatus(403);
    }

    /** @test */
    public function
    tester_cannot_view_result_from_public_url_if_quiz_is_private()
    {
        $quiz = QuizFactory::withQuestions(2)->create();

        $tester = $this->signIn();

        $test = Test::factory()->create([
            'quiz_id' => $quiz->id,
            'user_id' => $tester->id
        ]);

        $this->assertFalse($quiz->public);

        $this->get(route('result.public', $test))->assertStatus(403);
    }

    /** @test */
    public function guest_cannot_use_private_result_url_to_visit_result()
    {
        $quiz = QuizFactory::withQuestions(2)->create([
            'public' => true
        ]);

        $this->assertTrue($quiz->public);

        $test = Test::create([
            'user_id' => null,
            'quiz_id' => $quiz->id,
            'result' => 0
        ]);

        $this->get(route('result.show', $test))->assertStatus(403);
    }

    /** @test */
    public function guest_can_use_public_result_url_to_visit_result_if_quiz_is_public()
    {
        $quiz = QuizFactory::withQuestions(2)->create([
            'public' => true
        ]);

        $this->assertTrue($quiz->public);

        $test = Test::create([
            'user_id' => null,
            'quiz_id' => $quiz->id,
            'result' => 0
        ]);

        $this->get(route('result.public', $test))->assertStatus(200);
    }

    /** @test */
    public function user_with_view_test_result_permission_and_owner_of_the_quiz_can_view_tester_result()
    {
        $educator = UserFactory::withRole('educator')
            ->withPermissions(['view test result'])
            ->create();

        $quiz = QuizFactory::withQuestions(2)->create([
            'user_id' => $educator->id
        ]);

        $test = Test::factory()->create([
            'quiz_id' => $quiz->id
        ]);

        $this->actingAs($educator)->get("/result/test/{$test->getRouteKey()}")->assertStatus(200);
    }

    /** @test */
    public function user_with_view_test_result_permission_and_not_owner_of_the_quiz_cannot_view_tester_result()
    {
        $educator = UserFactory::withRole('educator')
            ->withPermissions(['view test result'])
            ->create();

        $anotherEducator = User::factory()->create();

        $anotherEducator->assignRole('educator');

        $quiz = QuizFactory::withQuestions(2)->create([
            'user_id' => $educator->id
        ]);

        $test = Test::factory()->create([
            'quiz_id' => $quiz->id
        ]);

        $this->actingAs($anotherEducator)->get("/result/test/{$test->getRouteKey()}")->assertStatus(403);
    }

    /** @test */
    public function user_with_mark_question_permission_can_mark_the_question_as_correct()
    {
        $educator = UserFactory::withRole('educator')
            ->withPermissions(['mark question'])
            ->create();

        $quiz = Quiz::factory()->create([
            'user_id' => $educator->id
        ]);

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

        $quiz = Quiz::factory()->create([
            'user_id' => $educator->id
        ]);

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

    /** @test */
    public function another_user_with_mark_question_permission_cannot_mark_the_question_correct_if_the_test_that_do_not_belongs_to_them()
    {
        $educator = UserFactory::withRole('educator')
            ->withPermissions(['mark question'])
            ->create();

        $anotherEducator = User::factory()->create();

        $anotherEducator->assignRole('educator');

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

        $this->actingAs($anotherEducator)->patch("/test-answer/{$testAnswers[1]->id}")->assertStatus(403);

        $this->assertTrue($testAnswers[1]->fresh()->correct);
    }

    /** @test */
    public function another_user_with_mark_question_permission_cannot_mark_the_question_incorrect_if_the_test_that_do_not_belongs_to_them()
    {
        $educator = UserFactory::withRole('educator')
            ->withPermissions(['mark question'])
            ->create();

        $anotherEducator = User::factory()->create();

        $anotherEducator->assignRole('educator');

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

        $this->actingAs($anotherEducator)->patch("/test-answer/{$testAnswers[1]->id}")->assertStatus(403);

        $this->assertTrue($testAnswers[1]->fresh()->correct);
    }
}