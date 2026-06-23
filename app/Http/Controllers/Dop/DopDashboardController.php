<?php

namespace App\Http\Controllers\Dop;

use App\Http\Controllers\Controller;
use App\Models\AttendanceLog;
use App\Models\Hotel;
use App\Models\User;
use App\Models\MaintenanceJob;
use App\Models\Complaint;
use App\Models\Department;
use App\Models\HousekeepingRoomAllocation;
use App\Models\RestaurantBooking;
use App\Models\RoomStatusUpdate;
use App\Models\RotaShift;
use Illuminate\Http\Request;

class DopDashboardController extends Controller
{

    public function index()
    {
        $today = today()->toDateString();

        $totalHotels = Hotel::count();
        $activeHotels = Hotel::where('is_active', true)->count();

        $totalStaff = User::whereHas('role', function ($query) {
                $query->whereRaw('LOWER(name) != ?', ['admin']);
            })
            ->count();

        $openMaintenance = MaintenanceJob::whereIn('status', ['pending', 'in_progress'])->count();

        $pendingComplaints = Complaint::where('status', 'pending')->count();

        $todayRestaurantBookings = RestaurantBooking::whereDate('booking_date', $today)
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->count();

        $hotels = Hotel::with([
                'dailyOperations' => function ($query) use ($today) {
                    $query->whereDate('operation_date', $today)
                        ->whereIn('snapshot', ['AM', 'PM']);
                },
            ])
            ->withCount([
                'users',
                'rooms',
                'departments',
                'restaurants',

                'maintenanceJobs as open_maintenance_count' => function ($query) {
                    $query->whereIn('status', ['pending', 'in_progress']);
                },

                'complaints as pending_complaints_count' => function ($query) {
                    $query->where('status', 'pending');
                },

                'roomStatusUpdates as ooo_rooms_count' => function ($query) {
                    $query->whereDate('status_date', today())
                        ->whereIn('status', ['OOO', 'OOI']);
                },

                'restaurantBookings as today_restaurant_bookings_count' => function ($query) {
                    $query->whereDate('booking_date', today())
                        ->whereNotIn('status', ['cancelled', 'no_show']);
                },

                'housekeepingRoomAllocations as today_hk_allocations_count' => function ($query) {
                    $query->whereDate('allocation_date', today());
                },

                'housekeepingRoomAllocations as today_hk_completed_count' => function ($query) {
                    $query->whereDate('allocation_date', today())
                        ->whereIn('cleaning_status', ['cleaned', 'inspected']);
                },
            ])
            ->latest()
            ->get();

        $hotels = $hotels->map(function ($hotel) {
            $amOperation = $hotel->dailyOperations
                ->where('snapshot', 'AM')
                ->first();

            $pmOperation = $hotel->dailyOperations
                ->where('snapshot', 'PM')
                ->first();

            $latestOperation = $pmOperation ?? $amOperation;

            $hotel->am_operation = $amOperation;
            $hotel->pm_operation = $pmOperation;
            $hotel->latest_operation = $latestOperation;

            $hotel->arrival_pickup = ($amOperation && $pmOperation)
                ? $pmOperation->arrivals - $amOperation->arrivals
                : null;

            $hotel->occupancy_variance = ($amOperation && $pmOperation)
                ? round($pmOperation->occupancy_percentage - $amOperation->occupancy_percentage, 1)
                : null;

            $healthScore = 100;

            $healthScore -= min($hotel->open_maintenance_count * 5, 30);
            $healthScore -= min($hotel->pending_complaints_count * 7, 35);
            $healthScore -= min($hotel->ooo_rooms_count * 4, 20);

            if ($hotel->today_hk_allocations_count > 0) {
                $hkCompletion = round(
                    ($hotel->today_hk_completed_count / $hotel->today_hk_allocations_count) * 100
                );

                if ($hkCompletion < 50) {
                    $healthScore -= 15;
                } elseif ($hkCompletion < 80) {
                    $healthScore -= 8;
                }
            } else {
                $hkCompletion = null;
            }

            if (!$hotel->is_active) {
                $healthScore = 0;
            }

            $hotel->health_score = max(0, min(100, $healthScore));
            $hotel->hk_completion = $hkCompletion;

            if ($hotel->health_score >= 85) {
                $hotel->health_level = 'excellent';
            } elseif ($hotel->health_score >= 70) {
                $hotel->health_level = 'good';
            } elseif ($hotel->health_score >= 50) {
                $hotel->health_level = 'warning';
            } else {
                $hotel->health_level = 'danger';
            }

            return $hotel;
        });

        $groupAlerts = [];

        foreach ($hotels as $hotel) {
            if (!$hotel->is_active) {
                $groupAlerts[] = [
                    'level' => 'muted',
                    'icon' => 'fa-circle-pause',
                    'hotel' => $hotel->name,
                    'message' => 'Hotel is inactive',
                ];
            }

            if (!$hotel->am_operation && !$hotel->pm_operation) {
                $groupAlerts[] = [
                    'level' => 'warning',
                    'icon' => 'fa-clipboard-list',
                    'hotel' => $hotel->name,
                    'message' => 'Daily operations forecast not submitted',
                ];
            }

            if ($hotel->am_operation && !$hotel->pm_operation) {
                $groupAlerts[] = [
                    'level' => 'warning',
                    'icon' => 'fa-moon',
                    'hotel' => $hotel->name,
                    'message' => 'PM forecast still pending',
                ];
            }

            if ($hotel->open_maintenance_count >= 5) {
                $groupAlerts[] = [
                    'level' => 'danger',
                    'icon' => 'fa-screwdriver-wrench',
                    'hotel' => $hotel->name,
                    'message' => $hotel->open_maintenance_count . ' open maintenance jobs',
                ];
            }

            if ($hotel->pending_complaints_count >= 3) {
                $groupAlerts[] = [
                    'level' => 'warning',
                    'icon' => 'fa-comments',
                    'hotel' => $hotel->name,
                    'message' => $hotel->pending_complaints_count . ' pending guest complaints',
                ];
            }

            if ($hotel->ooo_rooms_count >= 3) {
                $groupAlerts[] = [
                    'level' => 'danger',
                    'icon' => 'fa-door-closed',
                    'hotel' => $hotel->name,
                    'message' => $hotel->ooo_rooms_count . ' rooms out of order / inventory',
                ];
            }

            if (
                $hotel->today_hk_allocations_count > 0 &&
                $hotel->hk_completion !== null &&
                $hotel->hk_completion < 50
            ) {
                $groupAlerts[] = [
                    'level' => 'warning',
                    'icon' => 'fa-broom',
                    'hotel' => $hotel->name,
                    'message' => 'Housekeeping only ' . $hotel->hk_completion . '% completed today',
                ];
            }
        }

        $hotelsWithForecast = $hotels->filter(function ($hotel) {
            return $hotel->latest_operation !== null;
        });

        $groupAmOccupancy = $hotelsWithForecast->count() > 0
            ? round($hotelsWithForecast->avg(fn ($hotel) => $hotel->am_operation?->occupancy_percentage ?? 0), 1)
            : 0;

        $groupPmOccupancy = $hotelsWithForecast->count() > 0
            ? round($hotelsWithForecast->avg(fn ($hotel) => $hotel->pm_operation?->occupancy_percentage ?? $hotel->am_operation?->occupancy_percentage ?? 0), 1)
            : 0;

        $groupOccupancyVariance = round($groupPmOccupancy - $groupAmOccupancy, 1);

        $topOccupancyHotels = $hotels
            ->filter(fn ($hotel) => $hotel->latest_operation)
            ->sortByDesc(fn ($hotel) => $hotel->latest_operation->occupancy_percentage)
            ->take(5)
            ->values();

        $bottomOccupancyHotels = $hotels
            ->filter(fn ($hotel) => $hotel->latest_operation)
            ->sortBy(fn ($hotel) => $hotel->latest_operation->occupancy_percentage)
            ->take(5)
            ->values();

        $topPickupHotels = $hotels
            ->filter(fn ($hotel) => $hotel->arrival_pickup !== null)
            ->sortByDesc('arrival_pickup')
            ->take(5)
            ->values();

        $attentionHotels = $hotels
            ->filter(function ($hotel) {
                return
                    ($hotel->health_score ?? 0) < 70 ||
                    ($hotel->pending_complaints_count ?? 0) > 0 ||
                    ($hotel->open_maintenance_count ?? 0) > 0 ||
                    ($hotel->ooo_rooms_count ?? 0) > 0;
            })
            ->sortBy('health_score')
            ->take(6)
            ->values();

        $chartLabels = $hotels->pluck('name')->values();

        $amOccupancyData = $hotels->map(fn ($hotel) => $hotel->am_operation?->occupancy_percentage ?? 0)->values();
        $pmOccupancyData = $hotels->map(fn ($hotel) => $hotel->pm_operation?->occupancy_percentage ?? 0)->values();
        $pickupData = $hotels->map(fn ($hotel) => $hotel->arrival_pickup ?? 0)->values();
        $healthData = $hotels->map(fn ($hotel) => $hotel->health_score ?? 0)->values();
        $hkCompletionData = $hotels->map(fn ($hotel) => $hotel->hk_completion ?? 0)->values();
        $maintenanceData = $hotels->map(fn ($hotel) => $hotel->open_maintenance_count ?? 0)->values();
        $complaintsData = $hotels->map(fn ($hotel) => $hotel->pending_complaints_count ?? 0)->values();

        $totalArrivals = $hotels->sum(fn ($hotel) => $hotel->latest_operation?->arrivals ?? 0);
        $totalDepartures = $hotels->sum(fn ($hotel) => $hotel->latest_operation?->departures ?? 0);
        $totalStayovers = $hotels->sum(fn ($hotel) => $hotel->latest_operation?->stayovers ?? 0);
        $totalBreakfast = $hotels->sum(fn ($hotel) => $hotel->latest_operation?->expected_breakfast ?? 0);
        $totalDinner = $hotels->sum(fn ($hotel) => $hotel->latest_operation?->expected_dinner ?? 0);
        $totalArrivalPickup = $hotels->sum(fn ($hotel) => $hotel->arrival_pickup ?? 0);

        return view('dashboard.dop.index', compact(
            'totalHotels',
            'activeHotels',
            'totalStaff',
            'openMaintenance',
            'pendingComplaints',
            'todayRestaurantBookings',
            'hotels',
            'groupAlerts',
            'chartLabels',
            'amOccupancyData',
            'pmOccupancyData',
            'pickupData',
            'healthData',
            'hkCompletionData',
            'maintenanceData',
            'complaintsData',
            'totalArrivals',
            'totalDepartures',
            'totalStayovers',
            'totalBreakfast',
            'totalDinner',
            'totalArrivalPickup',
            'groupAmOccupancy',
            'groupPmOccupancy',
            'groupOccupancyVariance',
            'topOccupancyHotels',
            'bottomOccupancyHotels',
            'topPickupHotels',
            'attentionHotels'
        ));
    }


//hotel overview
public function hotelsOverview()
{
    $hotels = Hotel::withCount([
            'users',
            'rooms',
            'departments',
            'restaurants',

            'maintenanceJobs as open_maintenance_count' => function ($query) {
                $query->whereIn('status', ['pending', 'in_progress']);
            },

            'complaints as pending_complaints_count' => function ($query) {
                $query->where('status', 'pending');
            },

            'roomStatusUpdates as ooo_rooms_count' => function ($query) {
                $query->whereDate('status_date', today())
                    ->whereIn('status', ['OOO', 'OOI']);
            },

            'restaurantBookings as today_restaurant_bookings_count' => function ($query) {
                $query->whereDate('booking_date', today())
                    ->whereNotIn('status', ['cancelled', 'no_show']);
            },

            'housekeepingRoomAllocations as today_hk_allocations_count' => function ($query) {
                $query->whereDate('allocation_date', today());
            },

            'housekeepingRoomAllocations as today_hk_completed_count' => function ($query) {
                $query->whereDate('allocation_date', today())
                    ->whereIn('cleaning_status', ['cleaned', 'inspected']);
            },
        ])
        ->latest()
        ->get();

    $hotels = $hotels->map(function ($hotel) {
        $healthScore = 100;

        $healthScore -= min($hotel->open_maintenance_count * 5, 30);
        $healthScore -= min($hotel->pending_complaints_count * 7, 35);
        $healthScore -= min($hotel->ooo_rooms_count * 4, 20);

        if ($hotel->today_hk_allocations_count > 0) {
            $hkCompletion = round(
                ($hotel->today_hk_completed_count / $hotel->today_hk_allocations_count) * 100
            );

            if ($hkCompletion < 50) {
                $healthScore -= 15;
            } elseif ($hkCompletion < 80) {
                $healthScore -= 8;
            }
        } else {
            $hkCompletion = null;
        }

        if (!$hotel->is_active) {
            $healthScore = 0;
        }

        $hotel->health_score = max(0, min(100, $healthScore));
        $hotel->hk_completion = $hkCompletion;

        if ($hotel->health_score >= 85) {
            $hotel->health_level = 'excellent';
        } elseif ($hotel->health_score >= 70) {
            $hotel->health_level = 'good';
        } elseif ($hotel->health_score >= 50) {
            $hotel->health_level = 'warning';
        } else {
            $hotel->health_level = 'danger';
        }

        return $hotel;
    });

    return view('dashboard.dop.hotels-overview', compact('hotels'));
}





public function hotel(Hotel $hotel)
{
    $hotel->loadCount([
        'users',
        'rooms',
        'departments',
        'restaurants',

        'maintenanceJobs as open_maintenance_count' => function ($query) {
            $query->whereIn('status', ['pending', 'in_progress']);
        },

        'complaints as pending_complaints_count' => function ($query) {
            $query->where('status', 'pending');
        },

        'roomStatusUpdates as ooo_rooms_count' => function ($query) {
            $query->whereDate('status_date', today())
                ->whereIn('status', ['OOO', 'OOI']);
        },

        'housekeepingRoomAllocations as today_hk_allocations_count' => function ($query) {
            $query->whereDate('allocation_date', today());
        },

        'housekeepingRoomAllocations as today_hk_completed_count' => function ($query) {
            $query->whereDate('allocation_date', today())
                ->whereIn('cleaning_status', ['cleaned', 'inspected']);
        },
    ]);

    $recentMaintenance = $hotel->maintenanceJobs()
        ->with(['department', 'reporter', 'assignedUser'])
        ->latest()
        ->take(10)
        ->get();

    $recentComplaints = $hotel->complaints()
        ->latest()
        ->take(10)
        ->get();

    return view('dashboard.dop.hotels.show', compact(
        'hotel',
        'recentMaintenance',
        'recentComplaints'
    ));
}



//Maintanance
public function maintenance(Request $request)
{
    $query = MaintenanceJob::with([
        'hotel',
        'department',
        'reporter',
        'assignedUser',
    ]);

    if ($request->filled('hotel_id')) {
        $query->where('hotel_id', $request->hotel_id);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('priority')) {
        $query->where('priority', $request->priority);
    }

    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%")
                ->orWhere('room_number', 'like', "%{$search}%")
                ->orWhereHas('hotel', function ($hotelQuery) use ($search) {
                    $hotelQuery->where('name', 'like', "%{$search}%");
                });
        });
    }

    $jobs = $query
        ->latest()
        ->paginate(20)
        ->withQueryString();

    $hotels = Hotel::where('is_active', true)
        ->orderBy('name')
        ->get();

    $openJobs = MaintenanceJob::whereIn('status', ['pending', 'in_progress'])->count();

    $urgentJobs = MaintenanceJob::where('priority', 'urgent')
        ->whereIn('status', ['pending', 'in_progress'])
        ->count();

    $completedToday = MaintenanceJob::where('status', 'completed')
        ->whereDate('completed_date', today())
        ->count();

    $over24Hours = MaintenanceJob::whereIn('status', ['pending', 'in_progress'])
        ->where('created_at', '<=', now()->subHours(24))
        ->count();

    $hotelRankings = Hotel::withCount([
            'maintenanceJobs as open_maintenance_count' => function ($query) {
                $query->whereIn('status', ['pending', 'in_progress']);
            },
        ])
        ->orderByDesc('open_maintenance_count')
        ->take(8)
        ->get();

    return view('dashboard.dop.maintenance.index', compact(
        'jobs',
        'hotels',
        'openJobs',
        'urgentJobs',
        'completedToday',
        'over24Hours',
        'hotelRankings'
    ));
}

public function complaints(Request $request)
{
    $query = Complaint::with(['hotel', 'creator', 'handler']);

    if ($request->filled('hotel_id')) {
        $query->where('hotel_id', $request->hotel_id);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('priority')) {
        $query->where('priority', $request->priority);
    }

    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('guest_name', 'like', "%{$search}%")
                ->orWhere('room_number', 'like', "%{$search}%")
                ->orWhere('title', 'like', "%{$search}%")
                ->orWhere('category', 'like', "%{$search}%")
                ->orWhereHas('hotel', function ($hotelQuery) use ($search) {
                    $hotelQuery->where('name', 'like', "%{$search}%");
                });
        });
    }

    $complaints = $query->latest()
        ->paginate(20)
        ->withQueryString();

    $hotels = Hotel::where('is_active', true)
        ->orderBy('name')
        ->get();

    $pendingComplaints = Complaint::where('status', 'pending')->count();

    $inProgressComplaints = Complaint::where('status', 'in_progress')->count();

    $resolvedToday = Complaint::where('status', 'resolved')
        ->whereDate('updated_at', today())
        ->count();

    $urgentComplaints = Complaint::where('priority', 'urgent')
        ->whereIn('status', ['pending', 'in_progress'])
        ->count();

    $hotelRankings = Hotel::withCount([
            'complaints as pending_complaints_count' => function ($query) {
                $query->whereIn('status', ['pending', 'in_progress']);
            },
        ])
        ->orderByDesc('pending_complaints_count')
        ->take(8)
        ->get();

    return view('dashboard.dop.complaints.index', compact(
        'complaints',
        'hotels',
        'pendingComplaints',
        'inProgressComplaints',
        'resolvedToday',
        'urgentComplaints',
        'hotelRankings'
    ));
}

//staffing
public function staffing(Request $request)
{
    $date = $request->date ?? today()->toDateString();

    $hotels = Hotel::where('is_active', true)
        ->orderBy('name')
        ->get();

    $staffQuery = User::with(['hotel', 'department', 'role'])
        ->where('status', 'active')
        ->whereHas('role', function ($query) {
            $query->whereRaw('LOWER(name) != ?', ['admin']);
        });

    if ($request->filled('hotel_id')) {
        $staffQuery->where('hotel_id', $request->hotel_id);
    }

    if ($request->filled('department_id')) {
        $staffQuery->where('department_id', $request->department_id);
    }

    if ($request->filled('search')) {
        $search = $request->search;

        $staffQuery->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('job_title', 'like', "%{$search}%")
                ->orWhereHas('hotel', function ($hotelQuery) use ($search) {
                    $hotelQuery->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('department', function ($departmentQuery) use ($search) {
                    $departmentQuery->where('name', 'like', "%{$search}%");
                });
        });
    }

    $staff = $staffQuery
        ->orderBy('name')
        ->paginate(20)
        ->withQueryString();

    $departments = Department::whereNotNull('hotel_id')
        ->orderBy('name')
        ->get();

    $totalActiveStaff = User::where('status', 'active')
        ->whereHas('role', function ($query) {
            $query->whereRaw('LOWER(name) != ?', ['admin']);
        })
        ->count();

    $workingToday = RotaShift::whereDate('shift_date', $date)
        ->where('status', 'published')
        ->whereNotIn('shift_type', ['day_off', 'holiday', 'sick'])
        ->count();

    $dayOffToday = RotaShift::whereDate('shift_date', $date)
        ->whereIn('shift_type', ['day_off', 'holiday', 'sick'])
        ->count();

    $clockedInToday = AttendanceLog::whereDate('attendance_date', $date)
        ->where('status', 'clocked_in')
        ->count();

    $hotelRankings = Hotel::withCount([
            'users as active_staff_count' => function ($query) {
                $query->where('status', 'active');
            },
        ])
        ->orderByDesc('active_staff_count')
        ->take(8)
        ->get();

    return view('dashboard.dop.staffing.index', compact(
        'date',
        'hotels',
        'departments',
        'staff',
        'totalActiveStaff',
        'workingToday',
        'dayOffToday',
        'clockedInToday',
        'hotelRankings'
    ));
}

//Housekeeping
public function housekeeping(\Illuminate\Http\Request $request)
{
    $date = $request->date ?? today()->toDateString();

    $hotels = Hotel::where('is_active', true)
        ->orderBy('name')
        ->get();

    $query = HousekeepingRoomAllocation::with([
        'hotel',
        'room',
        'assignedTo',
        'roomStatusUpdate',
    ])
        ->whereDate('allocation_date', $date);

    if ($request->filled('hotel_id')) {
        $query->where('hotel_id', $request->hotel_id);
    }

    if ($request->filled('cleaning_status')) {
        $query->where('cleaning_status', $request->cleaning_status);
    }

    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->whereHas('room', function ($roomQuery) use ($search) {
                $roomQuery->where('room_number', 'like', "%{$search}%");
            })
            ->orWhereHas('assignedTo', function ($userQuery) use ($search) {
                $userQuery->where('name', 'like', "%{$search}%");
            })
            ->orWhereHas('hotel', function ($hotelQuery) use ($search) {
                $hotelQuery->where('name', 'like', "%{$search}%");
            });
        });
    }

    $allocations = $query
        ->latest()
        ->paginate(20)
        ->withQueryString();

    $base = HousekeepingRoomAllocation::whereDate('allocation_date', $date);

    if ($request->filled('hotel_id')) {
        $base->where('hotel_id', $request->hotel_id);
    }

    $totalAllocated = (clone $base)->count();

    $completed = (clone $base)
        ->whereIn('cleaning_status', ['cleaned', 'inspected'])
        ->count();

    $inProgress = (clone $base)
        ->where('cleaning_status', 'in_progress')
        ->count();

    $pending = (clone $base)
        ->whereIn('cleaning_status', ['assigned', 'pending'])
        ->count();

    $dndRefused = (clone $base)
        ->whereIn('cleaning_status', ['dnd', 'refused_service'])
        ->count();

    $oooRoomsQuery = RoomStatusUpdate::whereDate('status_date', $date)
        ->whereIn('status', ['OOO', 'OOI']);

    if ($request->filled('hotel_id')) {
        $oooRoomsQuery->where('hotel_id', $request->hotel_id);
    }

    $oooRooms = $oooRoomsQuery->count();

    $completionRate = $totalAllocated > 0
        ? round(($completed / $totalAllocated) * 100)
        : 0;

    $hotelRankings = Hotel::withCount([
            'housekeepingRoomAllocations as today_hk_allocations_count' => function ($query) use ($date) {
                $query->whereDate('allocation_date', $date);
            },
            'housekeepingRoomAllocations as today_hk_completed_count' => function ($query) use ($date) {
                $query->whereDate('allocation_date', $date)
                    ->whereIn('cleaning_status', ['cleaned', 'inspected']);
            },
        ])
        ->get()
        ->map(function ($hotel) {
            $hotel->hk_completion = $hotel->today_hk_allocations_count > 0
                ? round(($hotel->today_hk_completed_count / $hotel->today_hk_allocations_count) * 100)
                : 0;

            return $hotel;
        })
        ->sortBy('hk_completion')
        ->values();

    return view('dashboard.dop.housekeeping.index', compact(
        'date',
        'hotels',
        'allocations',
        'totalAllocated',
        'completed',
        'inProgress',
        'pending',
        'dndRefused',
        'oooRooms',
        'completionRate',
        'hotelRankings'
    ));
}


public function reports(Request $request)
{
    $date = $request->date ?? today()->toDateString();

    $hotels = Hotel::with([
            'dailyOperations' => function ($query) use ($date) {
                $query->whereDate('operation_date', $date)
                    ->whereIn('snapshot', ['AM', 'PM']);
            },
        ])
        ->withCount([
            'users',
            'rooms',
            'maintenanceJobs as open_maintenance_count' => function ($query) {
                $query->whereIn('status', ['pending', 'in_progress']);
            },
            'complaints as pending_complaints_count' => function ($query) {
                $query->whereIn('status', ['pending', 'in_progress']);
            },
            'housekeepingRoomAllocations as hk_total_count' => function ($query) use ($date) {
                $query->whereDate('allocation_date', $date);
            },
            'housekeepingRoomAllocations as hk_completed_count' => function ($query) use ($date) {
                $query->whereDate('allocation_date', $date)
                    ->whereIn('cleaning_status', ['cleaned', 'inspected']);
            },
        ])
        ->orderBy('name')
        ->get()
        ->map(function ($hotel) {
            $am = $hotel->dailyOperations->where('snapshot', 'AM')->first();
            $pm = $hotel->dailyOperations->where('snapshot', 'PM')->first();
            $latest = $pm ?? $am;

            $hotel->am_operation = $am;
            $hotel->pm_operation = $pm;
            $hotel->latest_operation = $latest;

            $hotel->pickup = ($am && $pm) ? $pm->arrivals - $am->arrivals : null;

            $hotel->hk_completion = $hotel->hk_total_count > 0
                ? round(($hotel->hk_completed_count / $hotel->hk_total_count) * 100)
                : 0;

            return $hotel;
        });

    return view('dashboard.dop.reports.index', compact('date', 'hotels'));
}
}