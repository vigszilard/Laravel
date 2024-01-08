<?php

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
use Laravel\Fortify\Fortify;

Fortify::loginView(function () {
    return view('login');
});

Fortify::registerView(function () {
    return view('register');
});

// routes/web.php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;


Route::get('/', [ArticleController::class, 'index'])->name('index');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register');


Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [ArticleController::class, 'dashboard'])->name('dashboard');

    Route::get('/submit-article', [ArticleController::class, 'showSubmissionForm'])->name('submitArticle');
    Route::post('/submit-article', [ArticleController::class, 'submitArticle'])->name('submitArticle');
    Route::post('/decline-article', [ArticleController::class, 'declineArticle'])->name('declineArticle');
    Route::post('/update-article', [ArticleController::class, 'updateArticle'])->name('updateArticle');
    Route::post('/approve-article', [ArticleController::class, 'approveArticle'])->name('approveArticle');

    Route::get('/article/{id}', [ArticleController::class, 'show'])->name('article');

});
