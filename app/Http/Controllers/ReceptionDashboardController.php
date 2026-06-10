<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceJob;
use App\Models\RestaurantBooking;
use Carbon\Carbon;

class ReceptionDashboardController extends Controller
{
public function index()
{
    $today = today();
    $yesterday = today()->subDay();

    $todayRestaurant = RestaurantBooking::whereDate('booking_date', $today)
        ->whereNotIn('status', ['cancelled', 'no_show'])
        ->count();

    $yesterdayRestaurant = RestaurantBooking::whereDate('booking_date', $yesterday)
        ->whereNotIn('status', ['cancelled', 'no_show'])
        ->count();

    $restaurantChange = $yesterdayRestaurant > 0
        ? round((($todayRestaurant - $yesterdayRestaurant) / $yesterdayRestaurant) * 100)
        : 0;

    $pendingRequests = RestaurantBooking::whereDate('booking_date', $today)
        ->where('status', 'confirmed')
        ->count();

    $days = collect(range(6, 0))->map(function ($i) {
        return now()->subDays($i);
    });

    $labels = [];
    $afternoonTea = [];
    $dinner = [];
    $spa = [];

    foreach ($days as $day) {
        $labels[] = $day->format('D');

        $afternoonTea[] = RestaurantBooking::where('booking_type', 'afternoon_tea')
            ->whereDate('booking_date', $day)
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->count();

        $dinner[] = RestaurantBooking::where('booking_type', 'dinner')
            ->whereDate('booking_date', $day)
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->count();

        $spa[] = 0;
    }

    // Maintenance Dashboard Data
    $openMaintenance = MaintenanceJob::whereIn('status', ['pending', 'in_progress'])->count();

    $urgentMaintenance = MaintenanceJob::where('priority', 'urgent')
        ->whereIn('status', ['pending', 'in_progress'])
        ->count();

    $recentMaintenance = MaintenanceJob::latest()
        ->take(8)
        ->get();

    $activeMaintenanceRooms = MaintenanceJob::whereNotNull('room_number')
        ->whereIn('status', ['pending', 'in_progress'])
        ->pluck('room_number')
        ->unique()
        ->values();

        $maintenanceStatusLabels = ['Pending', 'In Progress', 'Completed', 'Cancelled'];

        $maintenanceStatusData = [
            MaintenanceJob::where('status', 'pending')->count(),
            MaintenanceJob::where('status', 'in_progress')->count(),
            MaintenanceJob::where('status', 'completed')->count(),
            MaintenanceJob::where('status', 'cancelled')->count(),
        ];

        $maintenancePriorityLabels = ['Low', 'Medium', 'High', 'Urgent'];

        $maintenancePriorityData = [
            MaintenanceJob::where('priority', 'low')->whereIn('status', ['pending', 'in_progress'])->count(),
            MaintenanceJob::where('priority', 'medium')->whereIn('status', ['pending', 'in_progress'])->count(),
            MaintenanceJob::where('priority', 'high')->whereIn('status', ['pending', 'in_progress'])->count(),
            MaintenanceJob::where('priority', 'urgent')->whereIn('status', ['pending', 'in_progress'])->count(),
        ];

    return view('dashboard.reception.index', compact(
        'todayRestaurant',
        'restaurantChange',
        'pendingRequests',
        'labels',
        'afternoonTea',
        'dinner',
        'spa',
        'openMaintenance',
        'urgentMaintenance',
        'recentMaintenance',
        'activeMaintenanceRooms',
        'maintenanceStatusLabels',
        'maintenanceStatusData',
        'maintenancePriorityLabels',
        'maintenancePriorityData'
    ));
}
}