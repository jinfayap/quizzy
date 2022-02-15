<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class QuestionController extends Controller
{
    public function store(Quiz $quiz)
    {
        if ($quiz->user_id != auth()->id()) {
            abort(403);
        }

        if (in_array(request('question_type'), ['text', 'textarea'])) {
            $attributes = request()->validate([
                'question_text' => ['required'],
                'options' => ['nullable'],
                'answer' => ['required'],
                'question_type' => ['required', Rule::in(['text', 'textarea', 'select', 'radio', 'checkbox'])],
                'answer_explanation' => ['nullable'],
                'more_info_link' => ['nullable']
            ]);
        } elseif (in_array(request('question_type'), ['select', 'radio', 'checkbox'])) {
            $attributes = request()->validate([
                'question_text' => ['required'],
                'options' => ['present'],
                'answer' => ['required'],
                'question_type' => ['required', Rule::in(['text', 'textarea', 'select', 'radio', 'checkbox'])],
                'answer_explanation' => ['nullable'],
                'more_info_link' => ['nullable']
            ]);

            $attributes['answer'] = json_encode($attributes['answer']);
        }

        $attributes['quiz_id'] = $quiz->id;
        $attributes['user_id'] = auth()->id();

        $question = Question::create($attributes);
    }

    public function update(Quiz $quiz, Question $question)
    {
        if ($quiz->user_id != auth()->id()) {
            abort(403);
        }

        if (in_array(request('question_type'), ['text', 'textarea'])) {
            $attributes = request()->validate([
                'question_text' => ['required'],
                'options' => ['nullable'],
                'answer' => ['required'],
                'question_type' => ['required', Rule::in(['text', 'textarea', 'select', 'radio', 'checkbox'])],
                'answer_explanation' => ['nullable'],
                'more_info_link' => ['nullable']
            ]);
        } elseif (in_array(request('question_type'), ['select', 'radio', 'checkbox'])) {
            $attributes = request()->validate([
                'question_text' => ['required'],
                'options' => ['present'],
                'answer' => ['required'],
                'question_type' => ['required', Rule::in(['text', 'textarea', 'select', 'radio', 'checkbox'])],
                'answer_explanation' => ['nullable'],
                'more_info_link' => ['nullable']
            ]);

            $attributes['answer'] = json_encode($attributes['answer']);
        }

        $question->update($attributes);
    }

    public function destroy(Quiz $quiz, Question $question)
    {
        if ($quiz->user_id != auth()->id()) {
            abort(403);
        }

        $question->delete();
    }
}