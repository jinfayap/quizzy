<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::latest()->get();

        return view('quiz.index', compact('quizzes'));
    }

    public function create()
    {
        return view('quiz.create');
    }

    public function store()
    {
        $attributes = request()->validate([
            'title' => ['required'],
            'description' => ['nullable'],
            'duration' => ['nullable', 'integer']
        ]);

        $attributes['user_id'] = auth()->id();

        $quiz = Quiz::create($attributes);

        return redirect()->route('quiz.edit', $quiz)->with('flash', 'Quiz created!');
    }

    public function edit(Quiz $quiz)
    {
        $quiz->load('questions');

        return view('quiz.edit', compact('quiz'));
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