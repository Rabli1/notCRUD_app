<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OuvrageController;

Route::get('/', [OuvrageController::class, 'index'])->name('notcrud.index');
Route::post('/ouvrages', [OuvrageController::class, 'store'])->name('notcrud.store');
