<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ManageQuizTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function guest_cannot_add_question_to_any_quiz()
    {
        $quiz = Quiz::factory()->create();

        $question = Question::factory()->raw([
            'user_id' => $quiz->user_id,
            'quiz_id' => $quiz->id
            ]);

        $this->post("/quiz/{$quiz->getRouteKey()}/question", $question)->assertRedirect(route('login'));

        $this->assertDatabaseCount('questions', 0);

        $this->assertDatabaseMissing('questions', $question);
    }

    /** @test */
    public function non_owner_cannot_add_question_to_any_quiz()
    {
        $quiz = Quiz::factory()->create();

        $question = Question::factory()->raw([
            'user_id' => $quiz->user_id,
            'quiz_id' => $quiz->id
            ]);

        $nonOwner = $this->signIn();

        $this->post("/quiz/{$quiz->getRouteKey()}/question", $question)->assertStatus(403);

        $this->assertDatabaseCount('questions', 0);

        $this->assertDatabaseMissing('questions', $question);
    }

    /** @test */
    public function owner_can_add_question_to_their_quiz()
    {
        $user = $this->signIn();

        $quiz = Quiz::factory()->create(['user_id' => $user->id]);

        $question = Question::factory()->raw([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id
            ]);

        $this->post("/quiz/{$quiz->getRouteKey()}/question", $question);

        $this->assertDatabaseHas('questions', $question);
    }

    /** @test */
    public function guest_cannot_edit_the_question_in_any_quiz()
    {
        $quiz = Quiz::factory()->create();

        $question = Question::factory()->create([
            'user_id' => $quiz->user_id,
            'quiz_id' => $quiz->id,
            'question_text' => 'question text',
            'answer' => 'answer text',
            'options' => null,
            'question_type' => 'text',
            'answer_explanation' => 'answer explanation text',
            'more_info_link' => $this->faker->url(),
        ]);

        $this->patch("/quiz/{$quiz->getRouteKey()}/question/{$question->getRouteKey()}", $attributes = [
            'question_text' => 'question text changed',
            'answer' => 'answer text changed',
            'options' => null,
            'question_type' => 'text',
            'answer_explanation' => 'answer explanation text changed',
            'more_info_link' => null,
        ])->assertRedirect(route('login'));

        $this->assertDatabaseMissing('questions', $attributes);
    }

    /** @test */
    public function non_owner_cannot_edit_the_question_in_any_quiz()
    {
        $quiz = Quiz::factory()->create();

        $question = Question::factory()->create([
            'user_id' => $quiz->user_id,
            'quiz_id' => $quiz->id,
            'question_text' => 'question text',
            'answer' => 'answer text',
            'options' => null,
            'question_type' => 'text',
            'answer_explanation' => 'answer explanation text',
            'more_info_link' => $this->faker->url(),
        ]);

        $nonOwner = $this->signIn();

        $this->patch("/quiz/{$quiz->getRouteKey()}/question/{$question->getRouteKey()}", $attributes = [
            'question_text' => 'question text changed',
            'answer' => 'answer text changed',
            'options' => null,
            'question_type' => 'text',
            'answer_explanation' => 'answer explanation text changed',
            'more_info_link' => null,
        ])->assertStatus(403);

        $this->assertDatabaseMissing('questions', $attributes);
    }

    /** @test */
    public function owner_can_edit_the_question_in_their_quiz()
    {
        $user = $this->signIn();

        $quiz = Quiz::factory()->create(['user_id' => $user->id]);

        $question = Question::factory()->create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'question_text' => 'question text',
            'answer' => 'answer text',
            'options' => null,
            'question_type' => 'text',
            'answer_explanation' => 'answer explanation text',
            'more_info_link' => $this->faker->url(),
        ]);

        $this->patch("/quiz/{$quiz->getRouteKey()}/question/{$question->getRouteKey()}", $attributes = [
            'question_text' => 'question text changed',
            'answer' => 'answer text changed',
            'options' => null,
            'question_type' => 'text',
            'answer_explanation' => 'answer explanation text changed',
            'more_info_link' => null,
        ])->assertStatus(200);

        $this->assertDatabaseHas('questions', $attributes);
    }

    /** @test */
    public function guest_cannot_delete_the_question_in_any_quiz()
    {
        $quiz = Quiz::factory()->create();

        $question = Question::factory()->create([
            'user_id' => $quiz->user_id,
            'quiz_id' => $quiz->id,
        ]);

        $this->assertDatabaseCount('questions', 1);

        $this->delete("/quiz/{$quiz->getRouteKey()}/question/{$question->getRouteKey()}")->assertRedirect(route('login'));

        $this->assertDatabaseCount('questions', 1);
    }

    /** @test */
    public function non_owner_cannot_delete_the_question_in_any_quiz()
    {
        $quiz = Quiz::factory()->create();

        $question = Question::factory()->create([
            'user_id' => $quiz->user_id,
            'quiz_id' => $quiz->id,
        ]);

        $this->assertDatabaseCount('questions', 1);

        $nonOwner = $this->signIn();

        $this->delete("/quiz/{$quiz->getRouteKey()}/question/{$question->getRouteKey()}")->assertStatus(403);

        $this->assertDatabaseCount('questions', 1);
    }

    /** @test */
    public function owner_can_delete_question_in_their_quiz()
    {
        $user = $this->signIn();

        $quiz = Quiz::factory()->create(['user_id' => $user->id]);

        $question = Question::factory()->create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
        ]);

        $this->assertDatabaseCount('questions', 1);

        $this->delete("/quiz/{$quiz->getRouteKey()}/question/{$question->getRouteKey()}")->assertStatus(200);

        $this->assertDatabaseCount('questions', 0);
    }
}