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

//Route::view('/', 'welcome');
Route::post('/logout', function () {
    Auth::logout();

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login');
})->middleware('auth')->name('logout');

Route::middleware(['auth', 'verified'])->group(function () {
    // Vista Dashboard principal
    //Route::view('dashboard', 'dashboard')->name('dashboard');
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
    // Generación de PDF para Parte de Trabajo y Facturas
   Route::get('/work-orders/{id}/pdf/stream', [WorkOrderPdfController::class, 'stream'])->name('work-orders.pdf.stream');
    Route::get('/work-orders/{id}/pdf/download', [WorkOrderPdfController::class, 'download'])->name('work-orders.pdf.download');

    // Vista principal de facturas
    //Route::get('/invoices', InvoiceIndex::class)->name('invoices.index');
    Route::get('/invoices/{work_order_id?}', InvoiceIndex::class)->name('invoices.index');

    // Generar y ver PDF
    Route::get('/invoices/{invoice}/pdf', [InvoicePdfController::class, 'stream'])->name('invoices.pdf.stream');

    
    // Vista para crear Orden de Trabajo
    Route::get('/work-orders/create', CreateWorkOrder::class)->name('work-orders.create');
});

Route::get('/work-orders', WorkOrderIndex::class)->name('work-orders.index');

Route::get('/elevators', ElevatorIndex::class)->name('elevators.index');
Route::get('/elevators/{elevator}', ElevatorShow::class)->name('elevators.show');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

