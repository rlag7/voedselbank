<?php

use App\Http\Controllers\Employee\AllergyController;
use App\Http\Controllers\Employee\CustomerController;
use App\Http\Controllers\Employee\FoodPackageController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Authenticated routes
Route::patch('/employee/allergy/{allergy}/toggle', [AllergyController::class, 'toggle'])
    ->name('employee.allergy.toggle');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin-routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('suppliers', SupplierController::class);
});

// Employee-routes
Route::middleware(['auth', 'role:employee'])->prefix('employee')->name('employee.')->group(function () {
    Route::resource('food_packages', FoodPackageController::class);
    Route::resource('allergy', AllergyController::class);
    Route::resource('customers', CustomerController::class);

    Route::patch('/customers/{customer}/toggle-active', [CustomerController::class, 'toggleActive'])->name('customers.toggle-active');
});

require __DIR__.'/auth.php';
