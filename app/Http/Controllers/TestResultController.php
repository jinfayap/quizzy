<?php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Http\Request;

class TestResultController extends Controller
{
    public function index()
    {
        $tests = Test::latest()->where('user_id', auth()->id())->with(['quiz'])->get();

        // return $tests;

        return  view('result.index', compact('tests'));
    }

    public function show(Test $test)
    {
        $test->load(['quiz','testAnswers.question']);

        return view('result.show', compact('test'));
    }
}