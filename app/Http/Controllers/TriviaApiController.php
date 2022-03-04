<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Arr;

class TriviaApiController extends Controller
{
    public function store(Quiz $quiz)
    {

        if ($quiz->user_id != auth()->id()) {
            abort(403);
        }

        $attribute = request()->validate([
            'url' => ['required', 'url']
        ]);

        $results = Http::withoutVerifying()->get($attribute['url'])->json()['results'];

        $questions = array();

        foreach ($results as $question) {
            $options = Arr::shuffle([...$question['incorrect_answers'], $question['correct_answer']]);

            $optionsArr = array();

            foreach ($options as $option) {
                array_push($optionsArr, ['option' => $option]);
            }

            $question = Question::create([
                'quiz_id' => $quiz->id,
                'user_id' => auth()->id(),
                'question_text' => $question['question'],
                'question_type' => 'radio',
                'options' => $optionsArr,
                'answer' => json_encode([$question['correct_answer']])
            ]);

            array_push($questions, $question);
        }

        if (request()->expectsJson()) {
            return response()->json([
                'questions' => $questions
            ]);
        }
    }
}