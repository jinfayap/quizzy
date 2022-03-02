<?php

namespace Tests\Feature;

use App\Models\Quiz;
use App\Models\User;
use Facades\Tests\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class QuizTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function guest_cannot_create_new_quiz()
    {
        $quiz = Quiz::factory()->raw();

        $this->post("/quiz", $quiz)->assertRedirect(route('login'));

        $this->assertDatabaseMissing('quizzes', $quiz);
    }

    /** @test */
    public function authenticated_user_can_create_new_quiz()
    {
        $user = UserFactory::withRole('educator')
            ->withPermissions(['create quiz'])
            ->create();

        $quiz = Quiz::factory()->raw([
            'user_id' => $user->id
        ]);

        $this->actingAs($user)->post("/quiz", $quiz);

        $this->assertDatabaseHas('quizzes', $quiz);
    }

    /** @test */
    public function guest_cannot_edit_quiz()
    {
        $quiz = Quiz::factory()->create([
            'user_id' => User::factory(),
            'title' => 'quiz title',
            'description' => 'quiz description',
            'duration' => 20
        ]);

        $this->patch("/quiz/{$quiz->getRouteKey()}", $attributes = [
            'title' => 'title  changed',
            'description' => 'description changed',
            'duration' => 30,
            'public' => false
        ])->assertRedirect(route('login'));

        $this->assertDatabaseMissing('quizzes', $attributes);
    }

    /** @test */
    public function non_owner_cannot_edit_quiz()
    {
        $quiz = Quiz::factory()->create([
            'user_id' => User::factory(),
            'title' => 'quiz title',
            'description' => 'quiz description',
            'duration' => 20
        ]);

        $nonOwner = $this->signIn();

        $this->get("/quiz/{$quiz->getRouteKey()}/edit")->assertStatus(403);

        $this->patch("/quiz/{$quiz->getRouteKey()}", $attributes = [
            'title' => 'title  changed',
            'description' => 'description changed',
            'duration' => 30,
            'public' => false
        ])->assertStatus(403);

        $this->assertDatabaseMissing('quizzes', $attributes);
    }

    /** @test */
    public function owner_can_edit_their_quiz()
    {
        $user = $this->signIn();

        $quiz = Quiz::factory()->create([
            'user_id' => $user->id,
            'title' => 'quiz title',
            'description' => 'quiz description',
            'duration' => 20
        ]);

        $this->get("/quiz/{$quiz->getRouteKey()}/edit")->assertStatus(200);

        $this->patch("/quiz/{$quiz->getRouteKey()}", $attributes = [
            'title' => 'title  changed',
            'description' => 'description changed',
            'duration' => 30,
            'public' => false
        ])->assertStatus(200);

        $this->assertDatabaseHas('quizzes', $attributes);
    }

    /** @test */
    public function guest_cannot_delete_quiz()
    {
        $quiz = Quiz::factory()->create();

        $this->delete("/quiz/{$quiz->getRouteKey()}")->assertRedirect(route('login'));

        $this->assertDatabaseCount('quizzes', 1);
    }

    /** @test */
    public function non_owner_cannot_delete_quiz()
    {
        $quiz = Quiz::factory()->create();

        $nonOwner = $this->signIn();

        $this->delete("/quiz/{$quiz->getRouteKey()}")->assertStatus(403);

        $this->assertDatabaseCount('quizzes', 1);
    }

    /** @test */
    public function owner_can_delete_their_quiz()
    {
        $user = $this->signIn();

        $quiz = Quiz::factory()->create($attributes = [
            'user_id' => $user->id,
            'title' => 'quiz title',
            'description' => 'quiz description',
            'duration' => 20
        ]);

        $this->delete("/quiz/{$quiz->getRouteKey()}")->assertStatus(200);

        $this->assertDatabaseMissing('quizzes', $attributes);
    }
}