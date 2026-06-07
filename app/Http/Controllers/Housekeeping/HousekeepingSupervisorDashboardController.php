<?php

namespace App\Http\Controllers\Housekeeping;

use App\Http\Controllers\Controller;
use App\Models\HousekeepingRoomAllocation;

class HousekeepingSupervisorDashboardController extends Controller
{
        public function index()
    {
        $today = today();

        $todayAllocations = HousekeepingRoomAllocation::with([
                'room',
                'assignedTo',
                'roomStatusUpdate',
            ])
            ->whereDate('allocation_date', $today)
            ->get();

        $totalRooms = $todayAllocations->count();

        $pending = $todayAllocations->whereIn('cleaning_status', ['assigned', 'pending'])->count();
        $inProgress = $todayAllocations->where('cleaning_status', 'in_progress')->count();
        $cleaned = $todayAllocations->where('cleaning_status', 'cleaned')->count();
        $inspected = $todayAllocations->where('cleaning_status', 'inspected')->count();
        $dnd = $todayAllocations->where('cleaning_status', 'dnd')->count();
        $refused = $todayAllocations->where('cleaning_status', 'refused_service')->count();

        $departureRooms = $todayAllocations->filter(function ($allocation) {
            $status = strtolower($allocation->roomStatusUpdate->status ?? '');

            return in_array($status, [
                'departure',
                'room_move',
                'carry_forward',
            ]);
        })->count();

        $stayRooms = $todayAllocations->filter(function ($allocation) {
            $status = strtolower($allocation->roomStatusUpdate->status ?? '');

            return $status === 'stay';
        })->count();

        $allocatedMinutes =
            ($departureRooms * 30)
            +
            ($stayRooms * 15);

        $allocatedHours = round($allocatedMinutes / 60, 1);

        $departureRooms = $todayAllocations->filter(function ($allocation) {
            $status = strtolower($allocation->roomStatusUpdate->status ?? '');

            return in_array($status, [
                'departure',
                'room_move',
                'carry_forward',
            ]);
        })->count();

        $stayRooms = $todayAllocations->filter(function ($allocation) {
            $status = strtolower($allocation->roomStatusUpdate->status ?? '');

            return in_array($status, [
                'stay',
                'stayover',
            ]);
        })->count();

        $staffSummary = $todayAllocations
            ->groupBy('assigned_to')
            ->map(function ($items) {
                return [
                    'name' => $items->first()->assignedTo->name ?? 'Unknown Staff',
                    'rooms' => $items->count(),
                    'minutes' => $items->sum('estimated_minutes'),
                    'cleaned' => $items->whereIn('cleaning_status', ['cleaned', 'inspected'])->count(),
                    'pending' => $items->whereIn('cleaning_status', ['assigned', 'pending'])->count(),
                ];
            })
            ->values();

        $weekStart = now()->startOfWeek();
        $weekDays = collect();

        for ($i = 0; $i < 7; $i++) {
            $date = $weekStart->copy()->addDays($i);

            $allocations = HousekeepingRoomAllocation::whereDate('allocation_date', $date)->get();

            $weekDays->push([
                'day' => $date->format('D'),
                'date' => $date->format('d M'),
                'rooms' => $allocations->count(),
                'hours' => round($allocations->sum('estimated_minutes') / 60, 1),
            ]);
        }

        return view('dashboard.housekeeping.dashboard', compact(
            'totalRooms',
            'pending',
            'inProgress',
            'cleaned',
            'inspected',
            'dnd',
            'refused',
            'allocatedHours',
            'allocatedMinutes',
            'departureRooms',
            'stayRooms',
            'staffSummary',
            'weekDays'
        ));
    }
}