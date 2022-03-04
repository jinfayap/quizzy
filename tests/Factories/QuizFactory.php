<?php

namespace Tests\Factories;

use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;

class QuizFactory
{
    public $user = null;

    public $questionsCount = 0;

    public function ownedBy(User $user)
    {
        $this->user = $user;

        return $this;
    }

    public function withQuestions($count)
    {
        $this->questionsCount = $count;

        return $this;
    }

    public function create($attributes = [])
    {
        $quiz = Quiz::factory()->create(array_merge(
            [
                'user_id' => $this->user ?? $user = User::factory()->create()
            ],
            $attributes
        ));

        Question::factory($this->questionsCount)->create([
            'user_id' => $this->user ?? $user->id,
            'quiz_id' => $quiz->id
        ]);

        return $quiz;
    }
}