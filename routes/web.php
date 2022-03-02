<?php

use App\Http\Controllers\PermissionController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\TestAnswerController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\TestResultController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\UserTestController;
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


Route::post('/role', [RoleController::class, 'store'])->middleware(['auth', 'permission:create role']);
Route::delete('/role/{role}', [RoleController::class, 'destroy'])->middleware(['auth', 'permission:delete role']);

Route::post('/permission', [PermissionController::class, 'store'])->middleware(['auth', 'permission:create permission']);
Route::delete('/permission/{permission}', [PermissionController::class, 'destroy'])->middleware(['auth', 'permission:delete permission']);

Route::post('/role/{role}/permission/{permission}', [RolePermissionController::class, 'store'])->middleware(['auth', 'permission:assign role permission']);
Route::delete('/role/{role}/permission/{permission}', [RolePermissionController::class, 'destroy'])->middleware(['auth', 'permission:remove role permission']);

Route::post('/user/{user}/role/{role}', [UserRoleController::class, 'store'])->middleware(['auth', 'permission:assign user role']);
Route::delete('/user/{user}/role/{role}', [UserRoleController::class, 'destroy'])->middleware(['auth', 'permission:remove user role']);

Route::get('/admin', function () {
    return view('admin.index');
})->middleware(['auth', 'permission:view admin panel'])->name('admin.index');

Route::get('/admin/role', function () {
    return view('admin.role');
})->middleware(['auth', 'permission:view admin panel'])->name('admin.role');

Route::get('/admin/permission', function () {
    return view('admin.permission');
})->middleware(['auth', 'permission:view admin panel'])->name('admin.permission');

Route::get('/admin/role-permission', function () {
    return view('admin.role-permission');
})->middleware(['auth', 'permission:view admin panel'])->name('admin.role-permission');

Route::get('/admin/user-role-permission', function () {
    return view('admin.user-role-permission');
})->middleware(['auth', 'permission:view admin panel'])->name('admin.user-role-permission');

Route::get('/api/role', [RoleController::class, 'index'])->middleware(['permission:view api']);
Route::get('/api/permission', [PermissionController::class, 'index'])->middleware(['permission:view api']);
Route::get('/api/role-permission', [RolePermissionController::class, 'index'])->middleware(['permission:view api']);
Route::get('/api/user-role-permission', [UserRoleController::class, 'index'])->middleware(['permission:view api']);

Route::get('/test/quiz/{quiz}', [TestController::class, 'show'])->name('test.show');
Route::post('/test/quiz/{quiz}', [TestController::class, 'store'])->name('test.store');
Route::get('/public/test/quiz/{quiz}', [TestController::class, 'public'])->name('test.public');

Route::get('/result/test', [TestResultController::class, 'index'])->middleware(['auth'])->name('result.index');
Route::get('/result/test/{test}', [TestResultController::class, 'show'])->name('result.show');
Route::get('/public/result/test/{test}', [TestResultController::class, 'public'])->name('result.public');

Route::patch('/test-answer/{testAnswer}', [TestAnswerController::class, 'update'])->name('answer.update');
Route::delete('/test-answer/{testAnswer}', [TestAnswerController::class, 'destroy'])->name('answer.destroy');

Route::get('/user-test', [UserTestController::class, 'index'])->name('test.index');
Route::post('/invite/quiz/{quiz}', [UserTestController::class, 'store']);