<?php

use App\Http\Controllers\Auth\RegisteredUserController;
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

// unprotected route, for users that aren't logged in
Route::middleware(['guest'])->group(function () {
    Route::post('v1/register', [RegisteredUserController::class, 'store'])->name('register'); // create a user
});

Route::group(['prefix' => 'v1', 'middleware' => ['auth:sanctum']], function() {
    Route::get('/users', [UserController::class, 'index']); // get a list of users
});