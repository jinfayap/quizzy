<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\UserTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Quiz::latest()->where('user_id', auth()->id())->get();

        return view('quiz.index', compact('quizzes'));
    }

    public function show(Quiz $quiz)
    {
        if ($quiz->user_id != auth()->id()) {
            abort(403);
        }

        $quiz->loadCount('questions')->load('creator');

        return view('quiz.show', compact('quiz'));
    }

    public function create()
    {
        if (!Gate::forUser(auth()->user())->allows('create quiz')) {
            abort(403);
        }

        return view('quiz.create');
    }

    public function store()
    {
        if (!Gate::forUser(auth()->user())->allows('create quiz')) {
            abort(403);
        }

        $attributes = request()->validate([
            'title' => ['required'],
            'description' => ['nullable'],
            'duration' => ['nullable', 'integer'],
            'public' => ['required', 'boolean']
        ]);

        $attributes['user_id'] = auth()->id();

        $quiz = Quiz::create($attributes);

        return redirect()->route('quiz.edit', $quiz)->with('flash', 'Quiz created!');
    }

    public function edit(Quiz $quiz)
    {
        if ($quiz->user_id != auth()->id()) {
            abort(403);
        }

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
            'duration' => ['nullable', 'integer'],
            'public' => ['required', 'boolean']
        ]);

        $quiz->update($attributes);

        if ($quiz->public) {
            UserTest::where('quiz_id', $quiz->id)
            ->whereNull('attempt_date')->delete();
        }
    }

    public function destroy(Quiz $quiz)
    {
        if ($quiz->user_id != auth()->id()) {
            abort(403);
        }

        $quiz->delete();
    }
}