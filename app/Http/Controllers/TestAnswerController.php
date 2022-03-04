<?php

namespace App\Http\Controllers;

use App\Models\TestAnswer;
use Illuminate\Http\Request;

class TestAnswerController extends Controller
{
    public function update(TestAnswer $testAnswer)
    {
        $this->authorize('mark question');

        if ($testAnswer->test->quiz->user_id != auth()->id()) {
            abort(403);
        }

        $testAnswer->update([
            'correct' => true,
        ]);

        $testAnswer->test->setResult();

        return back();
    }

    public function destroy(TestAnswer $testAnswer)
    {
        if ($testAnswer->test->quiz->user_id != auth()->id()) {
            abort(403);
        }

        $testAnswer->update([
            'correct' => false,
        ]);

        $testAnswer->test->setResult();

        return back();
    }
}