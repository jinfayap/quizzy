<?php

namespace Tests\Feature\Question;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TextareaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_is_a_textarea_question()
    {
        $user = $this->signIn();

        $quiz = Quiz::factory()->create(['user_id' => $user->id]);

        $question = Question::factory()->textarea()->raw([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id
            ]);

        $this->post("/quiz/{$quiz->getRouteKey()}/question", $question);

        $this->assertDatabaseHas('questions', $question);
    }

    /** @test */
    public function a_textarea_question_can_be_edit()
    {
        $user = $this->signIn();

        $quiz = Quiz::factory()->create([
            'user_id' => $user->id
        ]);

        $question = Question::factory()->textarea()->create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id
        ]);

        $this->patch("/quiz/{$quiz->getRouteKey()}/question/{$question->getRouteKey()}", $attributes = [
            'question_text' => 'question text changed',
            'answer' => 'answer text changed',
            'question_type' => 'textarea',
        ])->assertStatus(200);

        $this->assertDatabaseHas('questions', $attributes);
    }
}