<?php

use App\Http\Controllers\QuizController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::get('/quiz', [QuizController::class, 'index'])->middleware('auth');
Route::post('/quiz', [QuizController::class, 'store'])->middleware('auth');
Route::patch('/quiz/{quiz}', [QuizController::class, 'update'])->middleware('auth');
Route::delete('/quiz/{quiz}', [QuizController::class, 'destroy'])->middleware('auth');