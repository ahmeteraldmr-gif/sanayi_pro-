<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\WorkOrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DiagnosticController;
use Illuminate\Support\Facades\Route;

// Landing page (tanıtım sayfası — giriş gerektirmez)
Route::get('/', fn() => view('landing'))->name('landing');

Route::middleware(['auth', 'verified'])->group(function () {
    // Admin Paneli Rotaları
    Route::middleware([\App\Http\Middleware\IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('branches', App\Http\Controllers\Admin\BranchController::class);
        Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    });

    // Usta Paneli Rotaları
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Müşteriler
    Route::resource('customers', CustomerController::class);

    // Araçlar
    Route::get('/vehicles/search', [VehicleController::class, 'search'])->name('vehicles.search');
    Route::get('/vehicles/quick-search', [VehicleController::class, 'quickSearch'])->name('vehicles.quick-search');
    Route::resource('vehicles', VehicleController::class);

    // Parçalar
    Route::get('/parts/ajax-search', [PartController::class, 'ajaxSearch'])->name('parts.ajax-search');
    Route::post('/parts/{part}/add-stock', [PartController::class, 'addStock'])->name('parts.add-stock');
    Route::resource('parts', PartController::class);

    // İş Emirleri & Görevler (Yapılacaklar)
    Route::get('/work-orders/{workOrder}/print', [WorkOrderController::class, 'print'])->name('work-orders.print');
    Route::patch('/work-orders/{workOrder}/status', [WorkOrderController::class, 'updateStatus'])->name('work-orders.status');
    Route::get('/work-orders/{workOrder}/notification-preview', [WorkOrderController::class, 'notificationPreview'])->name('work-orders.notification-preview');
    Route::post('/work-orders/{workOrder}/log-notification', [WorkOrderController::class, 'logNotification'])->name('work-orders.log-notification');
    Route::post('/work-orders/{workOrder}/tasks', [WorkOrderController::class, 'addTask'])->name('work-orders.tasks.add');
    Route::patch('/work-order-tasks/{task}/toggle', [WorkOrderController::class, 'toggleTask'])->name('work-order-tasks.toggle');
    Route::delete('/work-order-tasks/{task}', [WorkOrderController::class, 'deleteTask'])->name('work-order-tasks.delete');
    Route::resource('work-orders', WorkOrderController::class);

    // Raporlar
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // Servis Takvimi & Randevular
    Route::patch('/appointments/{appointment}/update-time', [\App\Http\Controllers\AppointmentController::class, 'updateTime'])->name('appointments.update-time');
    Route::resource('appointments', \App\Http\Controllers\AppointmentController::class);

    // Finans (Gelir, Gider, Tahsilatlar, Borçlar)
    Route::get('/finance', [\App\Http\Controllers\FinanceController::class, 'index'])->name('finance.index');

    // Tedarikçiler
    Route::get('/suppliers', [\App\Http\Controllers\SupplierController::class, 'index'])->name('suppliers.index');

    // Ustalar & Usta Performansı
    Route::get('/masters', [\App\Http\Controllers\MasterController::class, 'index'])->name('masters.index');
    Route::patch('/masters/{user}/role', [\App\Http\Controllers\MasterController::class, 'updateRole'])->name('masters.update-role');

    // Ayarlar
    Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');

    // ============================================================
    // 🤖 Akıllı Arıza Asistanı (Diagnostic Phase 2)
    // ============================================================
    Route::get('/diagnostic/knowledge-base', [DiagnosticController::class, 'knowledgeBase'])
         ->name('diagnostic.knowledge-base');
    Route::get('/diagnostic/new', [DiagnosticController::class, 'createStandalone'])
         ->name('diagnostic.new');
    Route::get('/diagnostic/vehicle-details/{vehicle}', [DiagnosticController::class, 'vehicleDetails'])
         ->name('diagnostic.vehicle-details');
    Route::post('/diagnostic/store', [DiagnosticController::class, 'storeStandalone'])
         ->name('diagnostic.store-standalone');
    Route::get('/diagnostic/session/{session}/analyze', [DiagnosticController::class, 'analyzeSession'])
         ->name('diagnostic.session-analyze');
    Route::post('/diagnostic/session/{session}/solution', [DiagnosticController::class, 'saveSolution'])
         ->name('diagnostic.save-solution');
    Route::post('/diagnostic/session/{session}/ai-feedback', [DiagnosticController::class, 'saveAiFeedback'])
         ->name('diagnostic.save-ai-feedback');

    // İş Emri İçi Uyumlu Rotalar (Backward Compatible)
    Route::get('/work-orders/{workOrder}/diagnostic', [DiagnosticController::class, 'create'])
         ->name('diagnostic.create');
    Route::post('/work-orders/{workOrder}/diagnostic', [DiagnosticController::class, 'store'])
         ->name('diagnostic.store');
    Route::get('/work-orders/{workOrder}/diagnostic/{session}/analyze', [DiagnosticController::class, 'analyze'])
         ->name('diagnostic.analyze');
    Route::post('/work-orders/{workOrder}/diagnostic/{session}/feedback', [DiagnosticController::class, 'feedback'])
         ->name('diagnostic.feedback');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
