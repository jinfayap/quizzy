<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\User;
use App\Models\UserTest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserTestController extends Controller
{
    public function index()
    {
        $publicTests = Quiz::where('public', true)->get();

        $tests = UserTest::where(function ($query) {
            $query->where('attempt_date', null)
                ->where('user_id', auth()->id());
        })
        ->orWhere(function ($query) {
            $query->where('start_date', '=', null)
                ->where('end_date', '=', null)
                ->where('attempt_date', null)
                ->where('user_id', auth()->id());
        })->with(['quiz'])->get();

        return view('test.index', compact('tests', 'publicTests'));
    }

    public function store(Quiz $quiz)
    {
        $attributes = request()->validate([
            'email' => ['required', 'email', Rule::exists('users', 'email')],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date']
        ]);

        $user = User::where('email', '=', $attributes['email'])->first();

        UserTest::create([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'start_date' => $attributes['start_date'] ?? null,
            'end_date' => $attributes['end_date'] ?? null,
        ]);
    }
}