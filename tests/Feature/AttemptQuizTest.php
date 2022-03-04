<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\Test;
use App\Models\TestAnswer;
use App\Models\User;
use App\Models\UserTest;
use Carbon\Carbon;
use Facades\Tests\Factories\QuizFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AttemptQuizTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function if_the_quiz_is_public_any_user_can_access_and_submit()
    {

        $quiz = QuizFactory::withQuestions(2)->create([
            'public' => true
        ]);

        $this->get("/public/test/quiz/{$quiz->getRouteKey()}")->assertStatus(200);

        $this->post("/test/quiz/{$quiz->getRouteKey()}", ['answers' => [
            1 => 'one',
            2 => 'two',
        ]]);

        $this->assertDatabaseCount('tests', 1);
        $this->assertDatabaseCount('test_answers', 2);

        $this->get('public/result/test/1')->assertStatus(200);
    }

    /** @test */
    public function if_the_quiz_is_changed_to_public_the_user_assign_test_is_removed_if_not_attempted()
    {
        $tester = User::factory()->create();

        $owner = $this->signIn();

        $quiz = QuizFactory::ownedBy($owner)
            ->withQuestions(2)
            ->create();

        UserTest::create([
            'user_id' => $tester->id,
            'quiz_id' => $quiz->id
        ]);

        UserTest::create([
            'user_id' => $owner->id,
            'quiz_id' => $quiz->id,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addDays(2)
        ]);

        $this->assertDatabaseCount('user_tests', 2);

        $this->patch("/quiz/{$quiz->getRouteKey()}", [
            'title' => 'title changed',
            'description' => 'description changed',
            'duration' => 30,
            'public' => true
        ]);

        $this->assertDatabaseCount('user_tests', 0);
    }

    /** @test */
    public function a_user_must_be_assigned_to_the_quiz_before_he_can_attempt_the_quiz()
    {
        $tester = User::factory()->create();

        $owner = $this->signIn();

        $quiz = QuizFactory::ownedBy($owner)
            ->withQuestions(2)
            ->create();

        $this->actingAs($tester)->get("/test/quiz/{$quiz->getRouteKey()}")->assertStatus(403);

        $this->assertDatabaseCount('user_tests', 0);

        $this->actingAs($owner)->post("/invite/quiz/{$quiz->getRouteKey()}", [
            'email' => $tester->email,
        ])->assertStatus(200);

        $this->assertDatabaseCount('user_tests', 1);

        $this->actingAs($tester)->get(route('test.show', $quiz))->assertStatus(200);
    }

    /** @test */
    public function when_a_user_submits_the_answer_to_the_quiz_test_it_stores_the_answers()
    {
        $user = $this->signIn();

        $quiz = QuizFactory::withQuestions(2)
            ->create();

        $this->post("/test/quiz/{$quiz->getRouteKey()}", ['answers' => [
            1 => 'one',
            2 => 'two',
        ]]);

        $this->assertDatabaseCount('tests', 1);
        $this->assertDatabaseCount('test_answers', 2);
    }

    /** @test */
    public function when_a_user_submits_the_answer_to_the_quiz_test_it_also_update_the_attempt_date_on_user_testss()
    {
        $tester = $this->signIn();

        $quiz = QuizFactory::withQuestions(2)->create();

        UserTest::create([
            'user_id' => $tester->id,
            'quiz_id' => $quiz->id
        ]);

        $this->actingAs($tester)->post("/test/quiz/{$quiz->getRouteKey()}", ['answers' => [
            1 => 'one',
            2 => 'two',
        ]]);

        $userTest = UserTest::where('user_id', $tester->id)
            ->where('quiz_id', $quiz->id)->first();

        $this->assertNotNull($userTest->attempt_date);
        $this->assertEquals(Carbon::now(), $userTest->attempt_date);
    }

    /** @test */
    public function once_the_tester_has_attempt_the_test_he_cannot_attempt_again()
    {
        $tester = $this->signIn();

        $quiz = QuizFactory::withQuestions(2)->create();

        $userTest = UserTest::create([
            'user_id' => $tester->id,
            'quiz_id' => $quiz->id
        ]);

        $this->actingAs($tester)->get("/test/quiz/{$quiz->getRouteKey()}")->assertStatus(200);

        $this->actingAs($tester)->post("/test/quiz/{$quiz->getRouteKey()}", ['answers' => [
            1 => 'one',
            2 => 'two',
        ]]);

        $this->actingAs($tester)->get("/test/quiz/{$quiz->getRouteKey()}")->assertStatus(403);
    }

    /** @test */
    public function tester_cannot_attempt_the_quiz_if_the_date_does_not_falls_between_the_start_and_end_date()
    {
        $tester = $this->signIn();

        $quiz = QuizFactory::withQuestions(2)->create();

        $userTest = UserTest::create([
            'user_id' => $tester->id,
            'quiz_id' => $quiz->id,
            'start_date' => Carbon::now()->subDay(4),
            'end_date' => Carbon::now()->subDay()
        ]);

        $this->actingAs($tester)->get("/test/quiz/{$quiz->getRouteKey()}")->assertStatus(403);
    }

    /** @test */
    public function the_first_valid_created_test_will_be_updated_if_the_user_attempts()
    {
        $tester = $this->signIn();

        $quiz = QuizFactory::withQuestions(2)->create();

        $userTestOne = UserTest::create([
            'user_id' => $tester->id,
            'quiz_id' => $quiz->id,
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addDays(3)
        ]);

        $userTestTwo = UserTest::create([
            'user_id' => $tester->id,
            'quiz_id' => $quiz->id,
        ]);

        $this->actingAs($tester)->post("/test/quiz/{$quiz->getRouteKey()}", ['answers' => [
            1 => 'one',
            2 => 'two',
        ]]);

        $this->assertNotNull($userTestOne->fresh()->attempt_date);
        $this->assertNull($userTestTwo->fresh()->attempt_date);
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

        $questionTwo = Question::factory()->select()->create([
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
