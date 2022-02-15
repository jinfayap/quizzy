<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function store()
    {
        $attributes = request()->validate([
            'title' => ['required'],
            'description' => ['nullable'],
            'duration' => ['nullable']
        ]);

        $attributes['user_id'] = auth()->id();

        Quiz::create($attributes);
    }

    public function update(Quiz $quiz)
    {
        if ($quiz->user_id != auth()->id()) {
            abort(403);
        }

        $attributes = request()->validate([
            'title' => ['required'],
            'description' => ['nullable'],
            'duration' => ['nullable']
        ]);

        $quiz->update($attributes);
    }

    public function destroy(Quiz $quiz)
    {
        if ($quiz->user_id != auth()->id()) {
            abort(403);
        }

        $quiz->delete();
    }
}