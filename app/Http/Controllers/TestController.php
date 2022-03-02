<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Test;
use App\Models\TestAnswer;
use App\Models\UserTest;
use Carbon\Carbon;

class TestController extends Controller
{
    public function public(Quiz $quiz)
    {
        $quiz->load('questions:id,quiz_id,question_text,options,question_type');

        return view('test.show', compact('quiz'));
    }

    public function show(Quiz $quiz)
    {
        $test = UserTest::where('quiz_id', $quiz->id)
            ->where(function ($query) {
                $query->where('start_date', '<=', Carbon::now())
                    ->where('end_date', '>=', Carbon::now())
                    ->where('attempt_date', null)
                    ->where('user_id', auth()->id());
            })
            ->orWhere(function ($query) {
                $query->where('start_date', '=', null)
                    ->where('end_date', '=', null)
                    ->where('attempt_date', null)
                    ->where('user_id', auth()->id());
            })
            ->get();

        $testCount = $test->count();

        if ($testCount == 0) {
            abort(403);
        }

        $quiz->load('questions:id,quiz_id,question_text,options,question_type');

        return view('test.show', compact('quiz'));
    }

    public function store(Quiz $quiz)
    {
        $test= Test::create([
            'user_id' => auth()->id(),
            'quiz_id' => $quiz->id
        ]);

        foreach ($answers = request('answers') as $key => $value) {
            $answer = TestAnswer::create([
                'question_id' => $key,
                'user_id' => auth()->id(),
                'test_id' => $test->id,
                'user_answer' => is_array($value) ? json_encode($value) : $value,
            ]);

            $questionType = $answer->question->question_type;
            $questionAnswer = $answer->question->answer;

            if (in_array($questionType, ['radio', 'select'])) {
                if (in_array($value, $questionAnswer)) {
                    $answer->markAsCorrect();
                };
            }

            if ($questionType == 'checkbox') {
                $correctAnswer = array_values($questionAnswer);

                $userAnswer = array_values($value);

                sort($correctAnswer);
                sort($userAnswer);

                if ($correctAnswer == $userAnswer) {
                    $answer->markAsCorrect();
                }
            }
        }

        $test->setResult();

        if (! $quiz->public) {
            $userTest = UserTest::where('quiz_id', $quiz->id)
         ->where(function ($query) {
             $query->where('start_date', '<=', Carbon::now())
                ->where('end_date', '>=', Carbon::now())
                ->where('attempt_date', null)
                ->where('user_id', auth()->id());
         })
        ->orWhere(function ($query) {
            $query->where('start_date', '=', null)
                ->where('end_date', '=', null)
                ->where('attempt_date', null)
                ->where('user_id', auth()->id());
        })->first();

            $userTest->update(['attempt_date' => Carbon::now()]);
        }

        if (request()->expectsJson()) {
            return response()->json(['test' => $test]);
        }
    }
}