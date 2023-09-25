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

Route::prefix('entries')->name('entries.')->group(function () {
    Route::get('', [\App\Http\Controllers\EntryController::class, 'index'])->name('index');
});

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
