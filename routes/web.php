<?php

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\UserRoleController;
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

Route::get('/quiz', [QuizController::class, 'index'])->middleware('auth')->name('quiz.index');
Route::get('/quiz/create', [QuizController::class, 'create'])->middleware('auth')->name('quiz.create');
Route::get('/quiz/{quiz}', [QuizController::class, 'show'])->middleware('auth')->name('quiz.show');
Route::get('/quiz/{quiz}/edit', [QuizController::class, 'edit'])->middleware('auth')->name('quiz.edit');
Route::post('/quiz', [QuizController::class, 'store'])->middleware('auth')->name('quiz.store');
Route::patch('/quiz/{quiz}', [QuizController::class, 'update'])->middleware('auth');
Route::delete('/quiz/{quiz}', [QuizController::class, 'destroy'])->middleware('auth')->name('quiz.destroy');

Route::post('/quiz/{quiz}/question', [QuestionController::class, 'store'])->middleware('auth');
Route::patch('/quiz/{quiz}/question/{question}', [QuestionController::class, 'update'])->middleware('auth');
Route::delete('/quiz/{quiz}/question/{question}', [QuestionController::class, 'destroy'])->middleware('auth');


Route::post('/role', [RoleController::class, 'store'])->middleware('permission:create role');
Route::delete('/role/{role}', [RoleController::class, 'destroy'])->middleware('permission:delete role');

Route::post('/permission', [PermissionController::class, 'store'])->middleware('permission:create permission');
Route::delete('/permission/{permission}', [PermissionController::class, 'destroy'])->middleware('permission:delete permission');

Route::post('/role/{role}/permission/{permission}', [RolePermissionController::class, 'store'])->middleware('permission:assign role permission');
Route::delete('/role/{role}/permission/{permission}', [RolePermissionController::class, 'destroy'])->middleware('permission:remove role permission');

Route::post('/user/{user}/role/{role}', [UserRoleController::class, 'store'])->middleware('permission:assign user role');
Route::delete('/user/{user}/role/{role}', [UserRoleController::class, 'destroy'])->middleware('permission:remove user role');
