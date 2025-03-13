<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\UserPreferenceArticleController;
use App\Http\Controllers\UserPreferenceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/resetpasssword', [AuthController::class, 'forgotPassword']);
Route::post('/resetpasssword-link', [AuthController::class, 'resetPassword'])->name('password.reset');
Route::middleware('auth:sanctum')->middleware(['throttle:api'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/fetch', [ArticleController::class, 'index']);
    Route::get('/fetchone/{id}', [ArticleController::class, 'indexOne']);
    Route::post('/getPreferences', [UserPreferenceController::class, 'getPreferences']);
    Route::post('/setPreferences', [UserPreferenceController::class, 'setPreferences']);
    Route::post('/fetchuserArticles', [UserPreferenceArticleController::class, 'fetchArticles']);
});

Route::post('/test', function (Request $request) {
    dd($request->all());
    return response()->json([
        'message' => 'POST request received',
        'data' => $request->all(),
    ]);
});



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
