<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CastCrmController;

Route::get('/', fn () => redirect()->route('crm.home'));

// CRM
Route::get('/crm', [CastCrmController::class, 'home'])->name('crm.home');
Route::get('/crm/memos/quick', [CastCrmController::class, 'memoQuick'])->name('crm.memos.quick');

// customers（※順番が重要：create → store → show）
Route::get('/crm/customers', [CastCrmController::class, 'customers'])->name('crm.customers');

Route::get('/crm/customers/create', [CastCrmController::class, 'customerCreate'])
    ->name('crm.customer.create');

Route::post('/crm/customers', [CastCrmController::class, 'customerStore'])
    ->name('crm.customer.store');

Route::get('/crm/customers/{id}', [CastCrmController::class, 'customerShow'])
    ->whereNumber('id')
    ->name('crm.customer.show');

Route::get('/crm/visits/create', [CastCrmController::class, 'visitCreate'])->name('crm.visits.create');
Route::get('/crm/reminders', [CastCrmController::class, 'reminders'])->name('crm.reminders');
Route::get('/crm/settings', [CastCrmController::class, 'settings'])->name('crm.settings');

Route::get('/crm/visits/unassigned', [CastCrmController::class, 'visitsUnassigned'])
    ->name('crm.visits.unassigned');

Route::get('/crm/visits/unassigned/{visitId}/assign', [CastCrmController::class, 'visitAssign'])
    ->whereNumber('visitId')
    ->name('crm.visits.assign');