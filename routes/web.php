<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;


Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
Route::post('/profile', [ProfileController::class, 'store'])->name('profile.store');
Route::get('/check/card/{credit_card}', [ProfileController::class, 'identicalDigits'])->name('credit.card');

require __DIR__.'/auth.php';
