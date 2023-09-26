<?php

/**
 * This file is part of the zidane-blog package.
 *
 * (c) Kamil Kozaczyński <kozaczynski.kamil@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return response()->json(['message' => 'ok']);
});

Route::prefix('auth')->name('auth.')->group(function () {
    Route::middleware('guest:sanctum')->group(function () {
        Route::post('login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
    });
});

Route::prefix('users')->name('users.')->group(function () {
    Route::post('', [\App\Http\Controllers\UserController::class, 'register'])->name('register');
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('me', [\App\Http\Controllers\UserController::class, 'me'])->name('me');
        Route::middleware('can:manage-users')->group(function () {
            Route::get('', [\App\Http\Controllers\UserController::class, 'index'])->name('index');
            Route::prefix('{user}')->group(function () {
                Route::patch('', [\App\Http\Controllers\UserController::class, 'show'])->name('show');
                Route::patch('', [\App\Http\Controllers\UserController::class, 'update'])->name('update');
                Route::delete('', [\App\Http\Controllers\UserController::class, 'delete'])->name('delete');
            });
        });
    });
    Route::middleware('guest:sanctum')->group(function () {
        Route::prefix('password/reset')->name('password.reset.')->group(function () {
            Route::post('init', [\App\Http\Controllers\UserController::class, 'initPasswordReset'])->name('init');
            Route::post('finish', [\App\Http\Controllers\UserController::class, 'finishPasswordReset'])->name('finish');
        });
    });
});

Route::prefix('entries')->name('entries.')->group(function () {
    Route::get('', [\App\Http\Controllers\EntryController::class, 'index'])->name('index');
    Route::prefix('{entry}')->group(function () {
        Route::get('', [\App\Http\Controllers\EntryController::class, 'show'])->name('show');
    });
    Route::middleware('auth:sanctum')->group(function () {
        Route::middleware('can:manage-entries')->group(function () {
            Route::post('', [\App\Http\Controllers\EntryController::class, 'store'])->name('store');

            Route::prefix('{entry}')->group(function () {
                Route::patch('', [\App\Http\Controllers\EntryController::class, 'update'])->name('update');
                Route::delete('', [\App\Http\Controllers\EntryController::class, 'delete'])->name('delete');
            });
        });
    });
});

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
