<?php

use App\Http\Controllers\Admin\ForecastGroupController;
use App\Http\Controllers\Admin\HotelController;
use App\Http\Controllers\Admin\MaintenanceController;
use App\Http\Controllers\Admin\MobileAppVersionController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\RoomController;
use App\Http\Controllers\Admin\RoomTypeController;
use App\Http\Controllers\AdminAttendanceSettingController;
use App\Http\Controllers\AdminDashboard;
use App\Http\Controllers\AttendanceQrController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FbDashboardController;
use App\Http\Controllers\Housekeeping\HousekeepingBoardController;
use App\Http\Controllers\Housekeeping\HousekeepingSupervisorDashboardController;
use App\Http\Controllers\Housekeeping\RoomAllocationController;
use App\Http\Controllers\Housekeeping\StaffWorkingTodayController;
use App\Http\Controllers\HousekeepingInspectionController;
use App\Http\Controllers\HousekeepingReportController;
use App\Http\Controllers\HousekeepingSupervisorRotaController;
// use App\Http\Controllers\Housekeeping\HousekeepingInspectionController;
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
use App\Http\Controllers\Reception\GroupBuffetBookingController;
use App\Http\Controllers\Reception\MealForecastController;
use App\Http\Controllers\Reception\RoomStatusController;
use App\Http\Controllers\ReceptionDashboardController;
use App\Http\Controllers\RestaurantBookingController;
use App\Http\Controllers\Admin\RestaurantBookingSettingController;
use App\Http\Controllers\RestaurantTableController;
use App\Http\Controllers\RotaController;
use App\Http\Controllers\SopController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\UserController;
use App\Models\MobileAppVersion;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;    
use App\Models\User;
use App\Services\FirebaseNotificationService;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\RestaurantController;
use App\Http\Controllers\Dop\DopDashboardController;
use App\Http\Controllers\Reception\DailyOperationController;

    // --- PUBLIC ROUTES ---
    Route::get('/', [LandingPageController::class, 'index'])->name('landing');
    Route::view('/privacy-policy', 'privacy-policy');
    Route::get('/attendance/live-qr', [AttendanceQrController::class, 'generate']);
    Route::get('/attendance/live-qr-screen', [AttendanceQrController::class, 'screen']);
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Guest Complaints (Public)
    Route::get('/guest/complaint', [ComplaintController::class, 'publicForm'])->name('guest.complaint.form');
    Route::post('/guest/complaint/store', [ComplaintController::class, 'store'])->name('guest.complaint.store');

    //Mobile reset password
    Route::get('/mobile-reset-password', function (\Illuminate\Http\Request $request) {
        return view('auth.mobile-reset-password-redirect', [
            'token' => $request->token,
            'email' => $request->email,
        ]);
    })->name('mobile.reset.password.redirect');

    //web ppassword screen
    Route::get('/reset-password', function (Request $request) {
        return view('auth.web-reset-password', [
            'token' => $request->token,
            'email' => $request->email,
        ]);
    })->name('password.reset.web');

    Route::get('/staff-app', function () {
    $latestVersion = MobileAppVersion::where('platform', 'android')
        ->where('is_latest', true)
        ->latest()
        ->first();

    return view('staff-app.download', compact('latestVersion'));
});

    // --- AUTHENTICATED ROUTES (General) ---
    // Only logged-in users can edit their own profile
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
        // Generic User Dashboard (Optional fallback)
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });

    // --- ADMIN ROUTES --- Access: Admins Only
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
        Route::get('/hotels/{hotel}/restaurants', [RestaurantController::class, 'index'])->name('admin.hotels.restaurants.index');
        Route::post('/hotels/{hotel}/restaurants', [RestaurantController::class, 'store'])->name('admin.hotels.restaurants.store');
        Route::put('/restaurants/{restaurant}', [RestaurantController::class, 'update'])->name('admin.restaurants.update');
        Route::delete('/restaurants/{restaurant}', [RestaurantController::class, 'destroy'])->name('admin.restaurants.destroy');
        Route::get('/hotels/{hotel}/restaurants/{restaurant}/settings',[RestaurantBookingSettingController::class, 'index'])->name('admin.restaurants.settings.index');
        Route::post('/hotels/{hotel}/restaurants/{restaurant}/settings',[RestaurantBookingSettingController::class, 'store'])->name('admin.restaurants.settings.store');
        //
        Route::get('/hotels/{hotel}/restaurants/{restaurant}/tables',[RestaurantTableController::class, 'index'])->name('admin.restaurants.tables.index');
        Route::post('/hotels/{hotel}/restaurants/{restaurant}/tables',[RestaurantTableController::class, 'store'])->name('admin.restaurants.tables.store');
        Route::put('/restaurant-tables/{table}',[RestaurantTableController::class, 'update'])->name('admin.restaurants.tables.update');
        Route::delete('/restaurant-tables/{table}',[RestaurantTableController::class, 'destroy'])->name('admin.restaurants.tables.destroy');
        Route::patch('/restaurant-tables/{table}/position', [RestaurantTableController::class, 'updatePosition'])->name('admin.restaurants.tables.update-position');
        Route::post('/hotels/{hotel}/restaurants/{restaurant}/floor-objects', [RestaurantTableController::class, 'storeFloorObject'])->name('admin.restaurants.floor-objects.store');
        Route::patch('/restaurant-floor-objects/{object}/position', [RestaurantTableController::class, 'updateFloorObjectPosition'])->name('admin.restaurants.floor-objects.update-position');
        Route::delete('/restaurant-floor-objects/{object}', [RestaurantTableController::class, 'destroyFloorObject'])->name('admin.restaurants.floor-objects.destroy');
        Route::get('/hotels/{hotel}/restaurants/{restaurant}/floor-plan',[RestaurantTableController::class, 'floorPlan'])->name('admin.restaurants.tables.floor-plan');


        // Room Types
        Route::get('/hotels/{hotel}/room-types', [RoomTypeController::class, 'index'])->name('admin.hotels.room-types.index');
        Route::post('/hotels/{hotel}/room-types', [RoomTypeController::class, 'store'])->name('admin.hotels.room-types.store');
        Route::put('/hotels/{hotel}/room-types/{roomType}', [RoomTypeController::class, 'update'])->name('admin.hotels.room-types.update');
        Route::delete('/hotels/{hotel}/room-types/{roomType}', [RoomTypeController::class, 'destroy'])->name('admin.hotels.room-types.destroy');

        // Rooms
        Route::get('/hotels/{hotel}/rooms', [RoomController::class, 'index'])->name('admin.hotels.rooms.index');
        Route::post('/hotels/{hotel}/rooms', [RoomController::class, 'store'])->name('admin.hotels.rooms.store');
        Route::put('/hotels/{hotel}/rooms/{room}', [RoomController::class, 'update'])->name('admin.hotels.rooms.update');
        Route::delete('/hotels/{hotel}/rooms/{room}', [RoomController::class, 'destroy'])->name('admin.hotels.rooms.destroy');

        //Mobile app update android
        Route::get('/mobile-app-versions', [MobileAppVersionController::class, 'index'])->name('mobile-app-versions.index');
        Route::get('/mobile-app-versions/create', [MobileAppVersionController::class, 'create'])->name('mobile-app-versions.create');
        Route::post('/mobile-app-versions', [MobileAppVersionController::class, 'store'])->name('mobile-app-versions.store');
        Route::patch('/mobile-app-versions/{version}/mark-latest', [MobileAppVersionController::class, 'markLatest'])->name('mobile-app-versions.mark-latest');
        Route::delete('/mobile-app-versions/{version}', [MobileAppVersionController::class, 'destroy'])->name('mobile-app-versions.destroy');

        Route::get('/forecast-groups', [ForecastGroupController::class, 'index'])->name('forecast-groups.index');
        Route::post('/forecast-groups', [ForecastGroupController::class, 'store'])->name('forecast-groups.store');
        Route::put('/forecast-groups/{forecastGroup}', [ForecastGroupController::class, 'update'])->name('forecast-groups.update');
        Route::delete('/forecast-groups/{forecastGroup}', [ForecastGroupController::class, 'destroy'])->name('forecast-groups.destroy');

        //Hotels
        Route::get('/hotels', [HotelController::class, 'index'])->name('admin.hotels.index');
        Route::get('/hotels/create', [HotelController::class, 'create'])->name('admin.hotels.create');
        Route::post('/hotels', [HotelController::class, 'store'])->name('admin.hotels.store');
        Route::put('/hotels/{hotel}', [HotelController::class, 'update'])->name('admin.hotels.update');
        Route::delete('/hotels/{hotel}', [HotelController::class, 'destroy'])->name('admin.hotels.destroy');

        //Department
        Route::get('/admin/departments', [DepartmentController::class, 'index'])->name('admin.departments.index');
        Route::get('/admin/departments/create', [DepartmentController::class, 'create'])->name('admin.departments.create');
        Route::post('/admin/departments', [DepartmentController::class, 'store'])->name('admin.departments.store');
        Route::get('/admin/departments/{department}/edit', [DepartmentController::class, 'edit'])->name('admin.departments.edit');
        Route::put('/admin/departments/{department}', [DepartmentController::class, 'update'])->name('admin.departments.update');
        Route::delete('/admin/departments/{department}', [DepartmentController::class, 'destroy'])->name('admin.departments.destroy');

        Route::get('/hotels/{hotel}/setup', [HotelController::class, 'setup'])->name('admin.hotels.setup');
        Route::get('/hotels/{hotel}/spa', [HotelController::class, 'spa'])->name('admin.hotels.spa');
        });

        // --- KITCHEN SUPERVISOR ROUTES ---
        // Access: Kitchen Supervisors Only
Route::middleware(['auth', 'role:Head-chef'])->prefix('kitchen-supervisor')->name('kitchen.')->group(function () {

        Route::get('/dashboard', [KitchenSupervisorController::class, 'dashboard'])->name('supervisor.dashboard');
        Route::get('/ai-prep-plan', [KitchenSupervisorController::class, 'aiPrepPlan'])->name('ai.prep');
        Route::get('/inventory', [KitchenInventoryController::class, 'index'])->name('inventory.index');
        Route::post('/inventory/store', [KitchenInventoryController::class, 'store'])->name('inventory.store');
        Route::post('/inventory/{item}/stock', [KitchenInventoryController::class, 'stockUpdate'])->name('inventory.stock');
        Route::put('/inventory/{item}/update', [KitchenInventoryController::class, 'update'])->name('inventory.update');
        Route::delete('/inventory/{item}', [KitchenInventoryController::class, 'destroy'])->name('inventory.destroy');
        Route::get('/current-inventory', [KitchenInventoryController::class, 'currentInventory'])->name('inventory.current');
        Route::get('/inventory-history', [KitchenInventoryController::class, 'history'])->name('inventory.history');
        Route::get('/inventory-history/pdf', [KitchenInventoryController::class, 'historyPdf'])->name('inventory.history.pdf');
        Route::get('/recipes', [KitchenRecipeController::class, 'index'])->name('recipes.index');
        Route::post('/recipes/store', [KitchenRecipeController::class, 'store'])->name('recipes.store');
        Route::put('/recipes/{menuItem}/update', [KitchenRecipeController::class, 'update'])->name('recipes.update');
        Route::delete('/recipes/{menuItem}', [KitchenRecipeController::class, 'destroy'])->name('recipes.destroy');
        Route::get('/current-recipes', [KitchenRecipeController::class, 'currentRecipes'])->name('recipes.current');
        Route::get('/buffets', [KitchenBuffetController::class, 'index'])->name('buffets.index');
        Route::post('/buffets/store', [KitchenBuffetController::class, 'store'])->name('buffets.store');
        Route::put('/buffets/{buffetMenu}/update', [KitchenBuffetController::class, 'update'])->name('buffets.update');
        Route::delete('/buffets/{buffetMenu}', [KitchenBuffetController::class, 'destroy'])->name('buffets.destroy');
        Route::post('/buffets/{buffetMenu}/sale', [KitchenBuffetController::class, 'storeSale'])->name('buffets.sale');
        Route::get('/wastage', [KitchenWastageController::class, 'index'])->name('wastage.index');
        Route::post('/wastage/store', [KitchenWastageController::class, 'store'])->name('wastage.store');
        Route::get('/rota', [KitchenSupervisorController::class, 'index'])->name('kitchensupervisor.rota.index');
        Route::post('/rota/store', [KitchenSupervisorController::class, 'storeRota'])->name('kitchensupervisor.rota.store');
        Route::delete('/rota/{id}', [KitchenSupervisorController::class, 'destroy'])->name('kitchensupervisor.rota.destroy');
        Route::get('/rota/view', [KitchenSupervisorController::class, 'view']) ->name('kitchensupervisor.rota.view');
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
        Route::post('/manager/attendance/manual-store', [ManagerAttendanceController::class, 'manualStore'])->name('manager.attendance.manualStore');
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
    Route::middleware(['auth', 'department:reception,front-office'])->prefix('reception')->name('reception.')->group(function () {
        Route::get('/dashboard', [ReceptionDashboardController::class, 'index'])->name('dashboard');
        Route::get('/restaurant-bookings', [RestaurantBookingController::class, 'index'])->name('restaurant.bookings.index');
        Route::get('/restaurant-bookings/{type}/slots', [RestaurantBookingController::class, 'slots'])->name('restaurant.bookings.slots');
        Route::get('/restaurant-bookings/{type}/slots/{slotStart}/{slotEnd}/create',[RestaurantBookingController::class, 'create'])->name('restaurant.bookings.create');
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
        //room plan
        Route::get('/room-status', [RoomStatusController::class, 'index'])->name('room-status.index');
        Route::post('/room-status/update', [RoomStatusController::class, 'update'])->name('room-status.update');

        Route::get('/group-buffets',[GroupBuffetBookingController::class, 'index'])->name('group-buffets.index');
        Route::get('/group-buffets/create',[GroupBuffetBookingController::class, 'create'])->name('group-buffets.create');
        Route::post('/group-buffets',[GroupBuffetBookingController::class, 'store'])->name('group-buffets.store');
        Route::put('/group-buffets/{groupBuffet}', [GroupBuffetBookingController::class, 'update'])->name('group-buffets.update');
        Route::get('/group-buffets-report', [GroupBuffetBookingController::class, 'dailyReport'])->name('group-buffets.daily-report');

        Route::get('/meal-forecasts', [MealForecastController::class, 'index'])->name('meal-forecasts.index');
        Route::post('/meal-forecasts/daily-total', [MealForecastController::class, 'storeOrUpdateDailyTotal'])->name('meal-forecasts.store-daily-total');
        Route::delete('/meal-forecasts/groups/{mealForecastGroup}', [MealForecastController::class, 'destroyGroup'])->name('meal-forecasts.groups.destroy');
        Route::get('/meal-forecasts/{mealForecast}/groups/create', [MealForecastController::class, 'createGroup'])->name('meal-forecasts.groups.create');
        Route::post('/meal-forecasts/{mealForecast}/groups', [MealForecastController::class, 'storeGroup'])->name('meal-forecasts.groups.store');
        Route::get('/meal-forecasts/report', [MealForecastController::class, 'report'])->name('meal-forecasts.report');

        //Daily Operations
        Route::get('/daily-operations', [DailyOperationController::class, 'index'])->name('daily-operations.index');
        Route::post('/daily-operations', [DailyOperationController::class, 'store'])->name('daily-operations.store');
 
    });


    // --- F&B ROUTES ---
    Route::middleware(['auth', 'department:fb,f-b,f-and-b,food-and-beverage'])->prefix('fb')->name('fb.')
    ->group(function () {
        Route::get('/dashboard', [FbDashboardController::class, 'index'])->name('dashboard');
        Route::get('/restaurant-bookings', [RestaurantBookingController::class, 'index'])->name('restaurant.bookings.index');
        Route::get('/restaurant-bookings/{type}/slots', [RestaurantBookingController::class, 'slots'])->name('restaurant.bookings.slots');
        Route::get('/floor-plan', [RestaurantTableController::class, 'floorPlan'])->name('floor-plan');
    });



    Route::middleware(['auth', 'department:housekeeping'])->prefix('housekeeping-supervisor')->name('housekeeping-supervisor.')
    ->group(function () {
        Route::get('/dashboard', [HousekeepingSupervisorDashboardController::class, 'index'])->name('dashboard');
        Route::get('/board', [HousekeepingBoardController::class, 'index'])->name('board.index');
        Route::get('/allocation', [RoomAllocationController::class, 'index'])->name('allocation.index');
        Route::post('/allocation/assign', [RoomAllocationController::class, 'assign'])->name('allocation.assign');
        Route::delete('/allocation/{allocation}/remove', [RoomAllocationController::class, 'remove'])->name('allocation.remove');
        Route::post('/allocation/add-extra-staff', [RoomAllocationController::class, 'addExtraStaff'])->name('allocation.add-extra-staff');
        Route::patch('/allocation/{rotaShift}/mark-unavailable', [RoomAllocationController::class, 'markUnavailable'])->name('allocation.mark-unavailable');
        Route::post('/allocation/auto-allocate', [RoomAllocationController::class, 'autoAllocate'])->name('allocation.auto-allocate');
        Route::get('/staff-working-today', [StaffWorkingTodayController::class, 'index'])->name('staff-working-today.index');
        Route::post('/staff-working-today/add-extra', [StaffWorkingTodayController::class, 'addExtraStaff'])->name('staff-working-today.add-extra');
        Route::patch('/staff-working-today/{rotaShift}/mark-unavailable', [StaffWorkingTodayController::class, 'markUnavailable'])->name('staff-working-today.mark-unavailable');
        Route::get('/inspection-queue', [HousekeepingInspectionController::class, 'index'])->name('inspection.index');
        Route::post('/inspection-queue/{allocation}/approve', [HousekeepingInspectionController::class, 'approve'])->name('inspection.approve');
        Route::post('/inspection-queue/{allocation}/reject', [HousekeepingInspectionController::class, 'reject'])->name('inspection.reject');
        Route::get('/dnd-pending-rooms', [HousekeepingInspectionController::class, 'staydnd'])->name('dnd-pending.index');
        Route::get('/out-of-order-rooms', [HousekeepingInspectionController::class, 'ooo'])->name('out-of-order.index');
        Route::get('/hk-rota', [HousekeepingSupervisorRotaController::class, 'index'])->name('hk-rota.index');
        Route::post('/hk-rota/save-draft', [HousekeepingSupervisorRotaController::class, 'saveDraft'])->name('hk-rota.save-draft');
        Route::delete('/hk-rota/{shift}/remove', [HousekeepingSupervisorRotaController::class, 'removeDraft'])->name('hk-rota.remove');
        Route::get('/hk-rota/view', [HousekeepingSupervisorRotaController::class, 'view'])->name('hk-rota.view');
        Route::get('/holiday-calendar', [HousekeepingSupervisorRotaController::class, 'holidayCalendar'])->name('holidays.calendar');
        Route::get('/reports/productivity', [HousekeepingReportController::class, 'productivity'])->name('reports.productivity');
        Route::get('/housekeeping/inspected-rooms', [HousekeepingInspectionController::class, 'inspectedRooms'])->name('inspectedRooms');
    });



Route::middleware(['auth', 'role:director-of-operations'])->prefix('director-of-operations')->name('dop.')->group(function () {
    Route::get('/dashboard', [DopDashboardController::class, 'index'])->name('dashboard');
    Route::get('/hotels/{hotel}', [DopDashboardController::class, 'hotel'])->name('hotels.show');
    Route::get('/hotels-overview', [DopDashboardController::class, 'hotelsOverview'])->name('hotels.overview');
    Route::get('/maintenance', [DopDashboardController::class, 'maintenance'])->name('maintenance.index');
    Route::get('/complaints', [DopDashboardController::class, 'complaints'])->name('complaints.index');
    Route::get('/staffing', [DopDashboardController::class, 'staffing'])->name('staffing.index');
    Route::get('/housekeeping', [DopDashboardController::class, 'housekeeping'])->name('housekeeping.index');
    Route::get('/reports', [DopDashboardController::class, 'reports'])->name('reports.index');
});

Route::get('/test-firebase-push', function () {
    $user = User::whereNotNull('fcm_token')->latest()->first();
    if (!$user) {
        return 'No user with FCM token found.';
    }
    $firebase = new FirebaseNotificationService();
    $response = $firebase->sendToToken(
        $user->fcm_token,
        'MGM Ops Test',
        'Push notification from Laravel is working 🎉',
        [
            'type' => 'test',
        ]
    );
    return response()->json($response);
});

// --- AUTHENTICATION ---
require __DIR__.'/auth.php';