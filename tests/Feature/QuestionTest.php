<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class QuestionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_is_a_text_question()
    {
        $this->withoutExceptionHandling();

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
    public function it_is_a_radio_question()
    {
        $this->withoutExceptionHandling();

        $user = $this->signIn();

        $quiz = Quiz::factory()->create(['user_id' => $user->id]);

        $question = Question::factory()->radio()->raw([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id
            ]);

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
    public function it_is_a_select_question()
    {
        $user = $this->signIn();

        $quiz = Quiz::factory()->create(['user_id' => $user->id]);

        $question = Question::factory()->select()->raw([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id
            ]);

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
    public function it_is_a_checkbox_question()
    {
        $user = $this->signIn();

        $quiz = Quiz::factory()->create(['user_id' => $user->id]);

        $question = Question::factory()->checkbox()->raw([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            ]);

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
}