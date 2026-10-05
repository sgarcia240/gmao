<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\AnalyticsDashboard;
use App\Livewire\CreateWorkOrder;
use App\Livewire\ElevatorIndex;
use App\Livewire\WorkOrderIndex; 
use App\Http\Controllers\WorkOrderPdfController;
use App\Livewire\InvoiceIndex;
use App\Http\Controllers\InvoicePdfController;
use App\Livewire\ElevatorShow;
use App\Livewire\UserIndex;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Auth;

// Redirección inicial al login
Route::get('/', function () {
    return redirect()->route('login');
});

// Cierre de sesión protegido
Route::post('/logout', function () {
    Auth::logout();

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login');
})->middleware('auth')->name('logout');

// GRUPO DE RUTAS PROTEGIDAS POR AUTENTICACIÓN
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard & Analítica
    Route::get('/analytics', AnalyticsDashboard::class)->name('analytics');

    // Rutas para Administradores y Supervisores
    Route::middleware([CheckRole::class . ':admin,supervisor'])->group(function () {
        Route::get('/users', UserIndex::class)->name('users.index');
    });

    // Rutas para Administradores, Supervisores y Técnicos
    Route::middleware([CheckRole::class . ':admin,supervisor,technician'])->group(function () {
        Route::get('/elevators', ElevatorIndex::class)->name('elevators.index');
        Route::get('/elevators/{elevator}', ElevatorShow::class)->name('elevators.show');
    });

    // Órdenes de Trabajo
    Route::get('/work-orders', WorkOrderIndex::class)->name('work-orders.index');
    Route::get('/work-orders/create', CreateWorkOrder::class)->name('work-orders.create');
    Route::get('/work-orders/{id}/pdf/stream', [WorkOrderPdfController::class, 'stream'])->name('work-orders.pdf.stream');
    Route::get('/work-orders/{id}/pdf/download', [WorkOrderPdfController::class, 'download'])->name('work-orders.pdf.download');

    // Facturación
    Route::get('/invoices/{work_order_id?}', InvoiceIndex::class)->name('invoices.index');
    Route::get('/invoices/{invoice}/pdf', [InvoicePdfController::class, 'stream'])->name('invoices.pdf.stream');
});

// Perfil de Usuario
Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

