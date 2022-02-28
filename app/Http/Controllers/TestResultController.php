<?php

namespace App\Http\Controllers;

use App\Models\Test;
use Illuminate\Http\Request;

class TestResultController extends Controller
{
    public function show(Test $test)
    {
        $test->load(['quiz','testAnswers.question']);

        // return $test;

        return view('result.show', compact('test'));
    }
}