<?php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TestResultController extends Controller
{
    public function index()
    {
        if (Gate::forUser(auth()->user())->allows('view test result')) {
            $tests = Test::latest()->with(['quiz', 'tester'])->get();
        } else {
            $tests = Test::latest()->where('user_id', auth()->id())->with(['quiz'])->get();
        }

        return  view('result.index', compact('tests'));
    }

    public function show(Test $test)
    {
        if ($test->user_id != auth()->id() && !Gate::forUser(auth()->user())->allows('view test result')) {
            abort(403);
        }

        $test->load(['quiz','testAnswers.question']);

        return view('result.show', compact('test'));
    }
}