<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\TicketController;
use App\Http\Controllers\Auth\AuthController;


Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');


Route::middleware(['auth'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', function () {
        return redirect()->route('tickets.index');
    });


    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/tickets/{id}', [TicketController::class, 'show'])->name('tickets.show');
    Route::get('/tickets/{id}/timeline', [TicketController::class, 'timeline'])->name('tickets.timeline');
    Route::patch('/tickets/{id}/status', [TicketController::class, 'status'])->name('tickets.status');
    Route::patch('/tickets/{id}/closure-request', [TicketController::class,'sendClosureRequest'])->name('tickets.closure.request');
    Route::patch('/tickets/{id}/reopen', [TicketController::class,'reopen'])->name('tickets.reopen');


    Route::get('/categories', [TicketController::class, 'categoryIndex'])->name('tickets.categories');
    Route::get('/categories/create', [TicketController::class, 'categoryCreate'])->name('tickets.create_category');
    Route::post('/categories', [TicketController::class, 'categoryStore'])->name('tickets.categoryStore');


    Route::get('/departments', [TicketController::class, 'departments'])->name('tickets.departments');
    Route::get('/departments/create', [TicketController::class, 'departmentCreate'])->name('tickets.create_department');
    Route::post('/departments', [TicketController::class, 'departmentStore'])->name('tickets.departmentStore');


    Route::get('/users/create', [AuthController::class, 'createUser'])->name('users.create');
    Route::post('/users', [AuthController::class, 'storeUser'])->name('users.store');

});
