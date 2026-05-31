<?php

use App\Http\Controllers\Admin\MaintenanceController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\AdminAttendanceSettingController;
use App\Http\Controllers\AdminDashboard;
use App\Http\Controllers\AttendanceQrController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FbDashboardController;
use App\Http\Controllers\KitchenBuffetController;
use App\Http\Controllers\KitchenInventoryController;
use App\Http\Controllers\KitchenRecipeController;
use App\Http\Controllers\KitchenSupervisorController;
use App\Http\Controllers\KitchenWastageController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\ManagerAttendanceController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\ManagerReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReceptionDashboardController;
use App\Http\Controllers\RestaurantBookingController;
use App\Http\Controllers\RestaurantBookingSettingController;
use App\Http\Controllers\RestaurantTableController;
use App\Http\Controllers\RotaController;
use App\Http\Controllers\SopController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;




// --- PUBLIC ROUTES ---
Route::get('/', [LandingPageController::class, 'index'])->name('landing');
Route::view('/privacy-policy', 'privacy-policy');
// Route::get('/attendance/live-qr', [AttendanceQrController::class, 'generate']);
Route::get('/attendance/live-qr', [AttendanceQrController::class, 'generate']);

Route::get('/attendance/live-qr-screen', [AttendanceQrController::class, 'screen']);
// Add these routes at the top of routes/web.php

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Guest Complaints (Public)
Route::get('/guest/complaint', [ComplaintController::class, 'publicForm'])->name('guest.complaint.form');
Route::post('/guest/complaint/store', [ComplaintController::class, 'store'])->name('guest.complaint.store');

// --- AUTHENTICATED ROUTES (General) ---
// Only logged-in users can edit their own profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Generic User Dashboard (Optional fallback)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// --- ADMIN ROUTES ---
// Access: Admins Only
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard
    Route::get('/admin/dashboard', [AdminDashboard::class, 'dashboard'])->name('admin.dashboard');

    // Employee Management
    Route::get('/showemployee', [AdminDashboard::class, 'index'])->name('dashboard.admin.showemp');
    Route::get('/addemp', [AdminDashboard::class, 'create'])->name('addemp');
    Route::post('/addemp', [AdminDashboard::class, 'store'])->name('addemp.store');
    Route::patch('/admin/users/{id}/status', [AdminDashboard::class, 'changeUserStatus'])->name('admin.users.status');
    Route::delete('/admin/users/{id}', [AdminDashboard::class, 'destroy'])->name('admin.users.destroy');

    // News Management
    Route::get('/admin/news/create', [NewsController::class, 'create'])->name('admin.news.create');
    Route::post('/admin/news/store', [NewsController::class, 'store'])->name('admin.news.store');
    Route::get('/admin/news', [NewsController::class, 'index'])->name('admin.news.index');
    Route::patch('/admin/news/status/{id}', [NewsController::class, 'changeStatus'])->name('admin.news.status');

    // Maintenance (Admin View)
    Route::get('/admin/maintenance', [MaintenanceController::class, 'index'])->name('admin.maintenance.index');
    Route::get('/admin/maintenance/create', [MaintenanceController::class, 'create'])->name('admin.maintenance.create');
    Route::post('/admin/maintenance/store', [MaintenanceController::class, 'store'])->name('admin.maintenance.store');
    Route::patch('/admin/maintenance/status/{id}', [MaintenanceController::class, 'changeStatus'])->name('admin.maintenance.status');

    // Complaints (Admin Overview)
    Route::get('/admin/complaints', [ComplaintController::class, 'index'])->name('admin.complaints.index');
    Route::patch('/admin/complaints/{id}/status', [ComplaintController::class, 'updateStatus'])->name('admin.complaints.status');

    //attendence settings
    Route::get('/admin/attendance-settings', [AdminAttendanceSettingController::class, 'edit'])->name('admin.attendance.settings');
    Route::post('/admin/attendance-settings', [AdminAttendanceSettingController::class, 'update'])->name('admin.attendance.settings.update');

    //resturant seetings
    Route::get('/restaurant/settings', [RestaurantBookingSettingController::class, 'index'])->name('restaurant.settings.index');
    Route::post('/restaurant/settings', [RestaurantBookingSettingController::class, 'store'])->name('restaurant.settings.store');
    Route::get('/restaurant/tables', [RestaurantTableController::class, 'index'])->name('restaurant.tables.index');
    Route::post('/restaurant/tables', [RestaurantTableController::class, 'store'])->name('restaurant.tables.store');
    Route::put('/restaurant/tables/{table}', [RestaurantTableController::class, 'update'])->name('restaurant.tables.update');
    Route::delete('/restaurant/tables/{table}', [RestaurantTableController::class, 'destroy'])->name('restaurant.tables.destroy');
    Route::get('/restaurant/tables/floor-plan', [RestaurantTableController::class, 'floorPlan'])->name('restaurant.tables.floor-plan');
});


// --- KITCHEN SUPERVISOR ROUTES ---
// Access: Kitchen Supervisors Only
Route::middleware(['auth', 'role:Head chef'])->group(function () {

    // Dashboard
    Route::get('/kitchen-supervisor/dashboard', [KitchenSupervisorController::class, 'dashboard'])->name('kitchen.supervisor.dashboard');

    // Inventory
    Route::get('/kitchen-supervisor/inventory', [KitchenInventoryController::class, 'index'])->name('kitchen.inventory.index');
    Route::post('/kitchen-supervisor/inventory/store', [KitchenInventoryController::class, 'store'])->name('kitchen.inventory.store');
    Route::post('/kitchen-supervisor/inventory/{item}/stock', [KitchenInventoryController::class, 'stockUpdate'])->name('kitchen.inventory.stock');
    Route::delete('/kitchen-supervisor/inventory/{item}', [KitchenInventoryController::class, 'destroy'])->name('kitchen.inventory.destroy');
    Route::get('/kitchen-supervisor/current-inventory', [KitchenInventoryController::class, 'currentInventory'])->name('kitchen.inventory.current');
    Route::put('/kitchen-supervisor/inventory/{item}/update', [KitchenInventoryController::class, 'update'])->name('kitchen.inventory.update');
    Route::get('/kitchen-supervisor/inventory-history', [KitchenInventoryController::class, 'history'])->name('kitchen.inventory.history');
    Route::get('/kitchen-supervisor/inventory-history/pdf', [KitchenInventoryController::class, 'historyPdf'])->name('kitchen.inventory.history.pdf');

    // Recipes
    Route::get('/kitchen-supervisor/recipes', [KitchenRecipeController::class, 'index'])->name('kitchen.recipes.index');
    Route::post('/kitchen-supervisor/recipes/store', [KitchenRecipeController::class, 'store'])->name('kitchen.recipes.store');
    Route::delete('/kitchen-supervisor/recipes/{menuItem}', [KitchenRecipeController::class, 'destroy'])->name('kitchen.recipes.destroy');
    Route::put('/kitchen-supervisor/recipes/{menuItem}/update', [KitchenRecipeController::class, 'update'])->name('kitchen.recipes.update');
    Route::get('/kitchen-supervisor/current-recipes', [KitchenRecipeController::class, 'currentRecipes'])->name('kitchen.recipes.current');

    // Buffet
    Route::get('/kitchen-supervisor/buffets', [KitchenBuffetController::class, 'index'])->name('kitchen.buffets.index');
    Route::post('/kitchen-supervisor/buffets/store', [KitchenBuffetController::class, 'store'])->name('kitchen.buffets.store');
    Route::post('/kitchen-supervisor/buffets/{buffetMenu}/sale', [KitchenBuffetController::class, 'storeSale'])->name('kitchen.buffets.sale');

    // Wastage
    Route::get('/kitchen-supervisor/wastage', [KitchenWastageController::class, 'index'])->name('kitchen.wastage.index');
    Route::post('/kitchen-supervisor/wastage/store', [KitchenWastageController::class, 'store'])->name('kitchen.wastage.store');

    // Rota
    Route::get('/kitchen-supervisor/rota',[KitchenSupervisorController::class, 'index'])->name('kitchensupervisor.rota.index');
    Route::post('/kitchen-supervisor/rota/store',[KitchenSupervisorController::class, 'storeRota'])->name('kitchensupervisor.rota.store');
    Route::delete('/kitchen-supervisor/rota/{id}',[KitchenSupervisorController::class, 'destroy'])->name('kitchensupervisor.rota.destroy');
    Route::get('/kitchen-supervisor/rota/view',[KitchenSupervisorController::class, 'view'])->name('kitchensupervisor.rota.view');
   
});



// --- MANAGER ROUTES ---
Route::middleware(['auth', 'role:manager'])->group(function () {
    // Dashboard
    Route::get('/manager/dashboard', [ManagerController::class, 'managerDashboard'])->name('manager.dashboard');

    // Maintenance (Manager Request)
    Route::get('/manager/maintenance', [ManagerController::class, 'maintenance'])->name('manager.maintenance.index');

    // Complaints
    Route::get('/manager/complaints', [ManagerController::class, 'complaints'])->name('manager.complaints.index');
    Route::patch('/manager/complaints/{id}/status', [ManagerController::class, 'updateComplaintStatus'])->name('manager.complaints.status');

    // Holidays
    Route::get('/manager/holidays', [ManagerController::class, 'holidays'])->name('manager.holidays.index');
    Route::patch('/manager/holidays/{id}/update', [ManagerController::class, 'updateHolidayStatus'])->name('manager.holidays.update');
    Route::get('/manager/holiday-calendar', [ManagerController::class, 'holidayCalendar'])->name('manager.holidays.calendar');

    // Reports
    Route::get('/manager/reports/holiday-pdf', [ManagerReportController::class, 'holidayReportForm'])->name('manager.reports.holiday.form');
    Route::post('/manager/reports/holiday-pdf', [ManagerReportController::class, 'generateHolidayPdf'])->name('manager.reports.holiday.generate');
    Route::get('/manager/reports', [ManagerReportController::class, 'index'])->name('manager.reports.index');
    Route::get('/manager/reports/maintenance', [ManagerReportController::class, 'maintenanceReport'])->name('manager.reports.maintenance');
    Route::get('/manager/reports/maintenance/pdf',[ManagerReportController::class, 'maintenancePdf'])->name('manager.reports.maintenance.pdf');

    // Rota Management
    Route::get('/manager/rota', [RotaController::class, 'index'])->name('manager.rota.index');
    Route::post('/manager/rota/store', [RotaController::class, 'store'])->name('manager.rota.store');
    Route::delete('/manager/rota/{id}', [RotaController::class, 'destroy'])->name('manager.rota.destroy');
    Route::patch('/manager/rota/publish', [RotaController::class, 'publish'])->name('manager.rota.publish');

    // Manager Rota View
    Route::get('/manager/rota/view', [RotaController::class, 'managerView'])->name('manager.rota.view');
    Route::get('/manager/rota/view/pdf', [RotaController::class, 'managerRotaPdf'])->name('manager.rota.pdf');
    
    //attendance access
    Route::get('/manager/attendance', [ManagerAttendanceController::class, 'index'])->name('manager.attendance.index');
    Route::put('/manager/attendance/{attendanceLog}', [ManagerAttendanceController::class, 'update'])->name('manager.attendance.update');
    Route::delete('/manager/attendance/{attendanceLog}', [ManagerAttendanceController::class, 'destroy'])->name('manager.attendance.destroy');

    //Attendence Report
    Route::get('/manager/attendance/monthly-report', [ManagerAttendanceController::class, 'monthlyReportForm'])->name('manager.attendance.monthly.form');
    Route::get('/manager/attendance/monthly-report/pdf', [ManagerAttendanceController::class, 'monthlyReportPdf'])->name('manager.attendance.monthly.pdf');
});

// --- SUPERVISOR ROUTES ---
// Access: Supervisors Only
Route::middleware(['auth', 'role:supervisor'])->group(function () {
    // Dashboard
    Route::get('/supervisor/dashboard', [SupervisorController::class, 'dashboard'])->name('supervisor.dashboard');

    // Holidays
    Route::get('/supervisor/holiday-calendar', [SupervisorController::class, 'holidayCalendar'])->name('supervisor.holidays.calendar');

    // Rota (Supervisor specific implementation)
    Route::get('/supervisor/rota', [RotaController::class, 'index'])->name('supervisor.rota.index');
    Route::post('/supervisor/rota/store', [SupervisorController::class, 'storeRota'])->name('supervisor.rota.store');
    Route::delete('/supervisor/rota/{id}', [RotaController::class, 'destroy'])->name('supervisor.rota.destroy');
    Route::get('/supervisor/rota/view', [SupervisorController::class, 'view'])->name('supervisor.rota.view');
});

// --- RECEPTION ROUTES ---
// --- RECEPTION ROUTES ---
Route::middleware(['auth', 'department:reception,front-office'])->prefix('reception')->name('reception.')->group(function () {
        Route::get('/dashboard', [ReceptionDashboardController::class, 'index'])->name('dashboard');
        Route::get('/restaurant-bookings', [RestaurantBookingController::class, 'index'])->name('restaurant.bookings.index');
        Route::get('/restaurant-bookings/{type}/slots', [RestaurantBookingController::class, 'slots'])->name('restaurant.bookings.slots');
        Route::get('/restaurant-bookings/{type}/slots/{slotStart}/{slotEnd}/create', [RestaurantBookingController::class, 'create'])->name('restaurant.bookings.create');
        Route::post('/restaurant-bookings/store', [RestaurantBookingController::class, 'store'])->name('restaurant.bookings.store');

        //report
        Route::get('/restaurant-bookings/report', [RestaurantBookingController::class, 'report'])->name('restaurant.bookings.report');
        Route::get('/restaurant-bookings/report/pdf', [RestaurantBookingController::class, 'reportPdf'])->name('restaurant.bookings.report.pdf');
        Route::get('/restaurant-bookings/list', [RestaurantBookingController::class, 'list'])->name('restaurant.bookings.list');

        //crud
        Route::get('/restaurant-bookings/{booking}', [RestaurantBookingController::class, 'show'])->name('restaurant.bookings.show');
        Route::get('/restaurant-bookings/{booking}/edit', [RestaurantBookingController::class, 'edit'])->name('restaurant.bookings.edit');
        Route::put('/restaurant-bookings/{booking}', [RestaurantBookingController::class, 'update'])->name('restaurant.bookings.update');
        Route::patch('/restaurant-bookings/{booking}/cancel', [RestaurantBookingController::class, 'cancel'])->name('restaurant.bookings.cancel');
        
    });


// --- F&B ROUTES ---
Route::middleware(['auth', 'department:fb,f-b,f-and-b,food-and-beverage'])
    ->prefix('fb')
    ->name('fb.')
    ->group(function () {

        Route::get('/dashboard', [FbDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/restaurant-bookings', [RestaurantBookingController::class, 'index'])
            ->name('restaurant.bookings.index');

        Route::get('/restaurant-bookings/{type}/slots', [RestaurantBookingController::class, 'slots'])
            ->name('restaurant.bookings.slots');

        Route::get('/floor-plan', [RestaurantTableController::class, 'floorPlan'])
            ->name('floor-plan');
    });


// --- AUTHENTICATION ---
require __DIR__.'/auth.php';