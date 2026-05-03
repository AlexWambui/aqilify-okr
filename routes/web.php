<?php

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use App\Http\Controllers\Guest\GuestController;
use App\Http\Controllers\OKRsController;
use App\Http\Controllers\Okrs\YearController;

Route::get('/', [GuestController::class, 'homePage'])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');

    Route::get('okrs', [OKRsController::class, 'index'])->name('okrs.index');

    Route::get('years', [YearController::class, 'index'])->name('years.index');
    Route::get('years/create', [YearController::class, 'create'])->name('years.create');
    Route::post('years', [YearController::class, 'store'])->name('years.store');
    Route::get('years/{year:year}/edit', [YearController::class, 'edit'])->name('years.edit');
    Route::put('years/{year:year}', [YearController::class, 'update'])->name('years.update');
    Route::delete('years/{year:year}', [YearController::class, 'destroy'])->name('years.destroy');
});

Route::inertia('/welcome', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('welcome');

require __DIR__.'/settings.php';
