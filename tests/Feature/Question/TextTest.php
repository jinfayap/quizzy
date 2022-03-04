<?php

namespace Tests\Feature\Question;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TextTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_be_created()
    {
        $user = $this->signIn();

        $quiz = Quiz::factory()->create(['user_id' => $user->id]);

        $question = Question::factory()->raw([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
        ]);

        $this->post("/quiz/{$quiz->getRouteKey()}/question", $question);

        $this->assertDatabaseHas('questions', $question);
    }

    /** @test */
    public function it_can_be_updated()
    {
        $user = $this->signIn();

        $quiz = Quiz::factory()->create([
            'user_id' => $user->id
        ]);

        $question = Question::factory()->create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id
        ]);

        $this->patch("/quiz/{$quiz->getRouteKey()}/question/{$question->getRouteKey()}", $attributes = [
            'question_text' => 'question text changed',
            'answer' => 'answer text changed',
            'question_type' => 'text',
        ])->assertStatus(200);

        $this->assertDatabaseHas('questions', $attributes);
    }

    /** @test */
    public function it_can_be_updated_to_a_choice_type_question()
    {
        $user = $this->signIn();

        $quiz = Quiz::factory()->create([
            'user_id' => $user->id
        ]);

        $question = Question::factory()->create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id
        ]);

        $this->assertEquals('text', $question->fresh()->question_type);

        $this->patch("/quiz/{$quiz->getRouteKey()}/question/{$question->getRouteKey()}", $attributes = [
            'question_text' => 'question text',
            'question_type' => 'select',
            'options' => [
                ['option' => 'one'],
                ['option' => 'two'],
                ['option' => 'three'],
                ['option' => 'four'],
            ],
            'answer' => ['one']
        ])->assertStatus(200);

        $this->assertNotEquals('text', $question->fresh()->question_type);

        $this->assertEquals('select', $question->fresh()->question_type);
    }
}
