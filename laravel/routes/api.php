<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StarWarsController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/star-wars', [StarWarsController::class, 'addPerson'])->name('star-wars');
Route::get('/star-wars', [StarWarsController::class, 'getJedi'])->name('star-wars-get');