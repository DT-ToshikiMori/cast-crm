<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CastCrmController;
use App\Http\Controllers\LineAuthController;

Route::get('/', fn () => redirect()->route('crm.home'));

// LINE Auth (no auth middleware — these are the login endpoints)
Route::post('/auth/line', [LineAuthController::class, 'login'])->name('auth.line');
Route::post('/auth/logout', [LineAuthController::class, 'logout'])->name('auth.logout');

// CRM — all routes require authentication
Route::middleware('auth')->group(function () {
    Route::get('/crm', [CastCrmController::class, 'home'])->name('crm.home');
    Route::get('/crm/memos/quick', [CastCrmController::class, 'memoQuick'])->name('crm.memos.quick');

    Route::get('/crm/customers', [CastCrmController::class, 'customers'])->name('crm.customers');

    Route::get('/crm/customers/create', [CastCrmController::class, 'customerCreate'])
        ->name('crm.customer.create');

    Route::post('/crm/customers', [CastCrmController::class, 'customerStore'])
        ->name('crm.customer.store');

    Route::get('/crm/customers/{id}', [CastCrmController::class, 'customerShow'])
        ->whereNumber('id')
        ->name('crm.customer.show');

    Route::post('/crm/customers/{id}/update', [CastCrmController::class, 'customerUpdate'])
        ->whereNumber('id')
        ->name('crm.customer.update');

    Route::get('/crm/visits/create', [CastCrmController::class, 'visitCreate'])->name('crm.visits.create');
    Route::post('/crm/visits', [CastCrmController::class, 'visitStore'])->name('crm.visits.store');

    Route::post('/crm/memos/quick', [CastCrmController::class, 'memoQuickStore'])->name('crm.memos.quickStore');

    Route::get('/crm/reminders', [CastCrmController::class, 'reminders'])->name('crm.reminders');
    Route::get('/crm/settings', [CastCrmController::class, 'settings'])->name('crm.settings');

    Route::get('/crm/visits/unassigned', [CastCrmController::class, 'visitsUnassigned'])
        ->name('crm.visits.unassigned');

    Route::get('/crm/visits/unassigned/{visitId}/assign', [CastCrmController::class, 'visitAssign'])
        ->whereNumber('visitId')
        ->name('crm.visits.assign');

    Route::post('/crm/visits/unassigned/{visitId}/assign', [CastCrmController::class, 'visitAssignStore'])
        ->whereNumber('visitId')
        ->name('crm.visits.assignStore');
});

// LIFF initialization page (no auth required)
Route::get('/liff-init', fn () => view('liff-init'))->name('liff.init');
