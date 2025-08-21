<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Accounts\AccountsController;

/*
|--------------------------------------------------------------------------
| Accounts Routes
|--------------------------------------------------------------------------
|
| Here is where you can register accounts related routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['auth', 'role:accountant|admin|super_admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AccountsController::class, 'dashboard'])->name('dashboard');
    
    // Income
    Route::get('/income', [AccountsController::class, 'income'])->name('income.index');
    Route::post('/income', [AccountsController::class, 'storeIncome'])->name('income.store');
    
    // Expenses
    Route::get('/expenses', [AccountsController::class, 'expenses'])->name('expenses.index');
    Route::post('/expenses', [AccountsController::class, 'storeExpense'])->name('expenses.store');
    
    // Reports
    Route::get('/reports', [AccountsController::class, 'reports'])->name('reports.index');
    
    // Student Fees
    Route::get('/fees', [AccountsController::class, 'studentFees'])->name('fees.index');
    
    // Payments
    Route::post('/fees/{fee}/payments', [AccountsController::class, 'recordPayment'])->name('fees.payments.store');
    Route::get('/fees/{fee}/payments/history', [AccountsController::class, 'getPaymentHistory'])->name('fees.payments.history');
});
