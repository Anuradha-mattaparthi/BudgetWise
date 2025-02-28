<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FamilyDetailController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IncomeController;

Route::view('/', 'welcome');
Route::get('/dashboard', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Family Details Routes
Route::get('/family-details/create', [FamilyDetailController::class, 'create'])
    ->middleware(['auth'])
    ->name('family-details.create');
Route::post('/family-details', [FamilyDetailController::class, 'store'])
    ->middleware(['auth'])
    ->name('family-details.store');

// Family Detail Show/Update Routes
Route::get('/family-details/{id}', [FamilyDetailController::class, 'show'])->name('family-details.show');
Route::put('/family-details/{id}', [FamilyDetailController::class, 'update'])->name('family-details.update');

// Expenses and Incomes Routes
Route::middleware(['auth'])->group(function () {

    // Expense Routes
    Route::get('/expenses/create/{familyDetail}', [ExpenseController::class, 'create'])->name('expenses.create');
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::put('/expenses/{id}', [ExpenseController::class, 'update'])->name('expenses.update'); // Update route for expense
    Route::delete('/expenses/{id}', [ExpenseController::class, 'destroy'])->name('expenses.destroy'); // Delete route for expense

    // Income Routes
    Route::get('/incomes/create/{familyDetail}', [IncomeController::class, 'create'])->name('incomes.create');
    Route::post('/incomes', [IncomeController::class, 'store'])->name('incomes.store');
    Route::put('/incomes/{id}', [IncomeController::class, 'update'])->name('incomes.update');
    Route::delete('/incomes/{id}', [IncomeController::class, 'destroy'])->name('incomes.destroy');
});

// Get Expenses and Incomes
Route::get('/get-expenses-incomes', [ExpenseController::class, 'getExpensesIncomes']);

// Download Routes
Route::get('/family-details/{familyDetail}/download-expenses', [FamilyDetailController::class, 'downloadExpenses'])
    ->name('family-details.downloadExpenses');
Route::get('/family-details/{familyDetail}/download-incomes', [FamilyDetailController::class, 'downloadIncomes'])
    ->name('family-details.downloadIncomes');

// Authentication Routes
require __DIR__.'/auth.php';
