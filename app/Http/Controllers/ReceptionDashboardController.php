<?php

namespace App\Http\Controllers;

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

            $spa[] = 0; // spa module later
        }

        return view('dashboard.reception.index', compact(
            'todayRestaurant',
            'restaurantChange',
            'pendingRequests',
            'labels',
            'afternoonTea',
            'dinner',
            'spa'
        ));
    }
}