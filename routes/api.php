<?php

use App\Http\Controllers\ProjectKeywordController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('signup',[UserController::class,'signup']);
Route::post('login',[UserController::class,'login']);

Route::post('update-keywords',[ProjectKeywordController::class,'updateKeywords']);
Route::post('forgot-password', [UserController::class, 'sendResetLinkEmail'])->middleware('guest')->name('password.email');
Route::apiResources([
    'user' => UserController::class,
    'keyword'=>ProjectKeywordController::class,
    'proposal'=>ProposalController::class,

  ]);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
