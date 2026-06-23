<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AttendanceLog;
use App\Models\HousekeepingRoomAllocation;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HousekeepingReportController extends Controller
{
    public function productivity(Request $request)
    {
        $hotelId = auth()->user()->hotel_id;

        $date = $request->date ?? today()->toDateString();

        $departureMinutes = 30;
        $stayMinutes = 15;

        $hkStaff = User::with('department')
            ->where('hotel_id', $hotelId)
            ->where('status', 'active')
            ->whereHas('department', function ($query) use ($hotelId) {
                $query->where('hotel_id', $hotelId)
                    ->whereIn('name', [
                        'Housekeeping',
                        'HK',
                        'House Keeping',
                    ]);
            })
            ->orderBy('name')
            ->get();

        $reports = $hkStaff->map(function ($staff) use (
            $date,
            $departureMinutes,
            $stayMinutes,
            $hotelId
        ) {
            $attendance = AttendanceLog::where('user_id', $staff->id)
                ->whereDate('attendance_date', $date)
                ->first();

            $workedMinutes = 0;

            if ($attendance && $attendance->clock_in_at && $attendance->clock_out_at) {
                $clockIn = Carbon::parse($attendance->clock_in_at);
                $clockOut = Carbon::parse($attendance->clock_out_at);

                if ($clockOut->lessThan($clockIn)) {
                    $clockOut->addDay();
                }

                $workedMinutes = $clockIn->diffInMinutes($clockOut);
            }

            $allocations = HousekeepingRoomAllocation::with('roomStatusUpdate')
                ->where('hotel_id', $hotelId)
                ->where('assigned_to', $staff->id)
                ->whereDate('allocation_date', $date)
                ->whereIn('cleaning_status', [
                    'cleaned',
                    'inspected',
                ])
                ->get();

            $departures = $allocations->filter(function ($allocation) {
                $status = strtolower($allocation->roomStatusUpdate->status ?? '');

                return in_array($status, [
                    'departure',
                    'room_move',
                    'carry_forward',
                ]);
            })->count();

            $stays = $allocations->filter(function ($allocation) {
                $status = strtolower($allocation->roomStatusUpdate->status ?? '');

                return in_array($status, [
                    'stay',
                    'stayover',
                ]);
            })->count();

            $expectedMinutes = ($departures * $departureMinutes) + ($stays * $stayMinutes);

            $productivity = $workedMinutes > 0
                ? round(($expectedMinutes / $workedMinutes) * 100, 1)
                : 0;

            return [
                'staff_name' => $staff->name,
                'clock_in' => $attendance?->clock_in_at,
                'clock_out' => $attendance?->clock_out_at,
                'worked_minutes' => $workedMinutes,
                'departures' => $departures,
                'stays' => $stays,
                'expected_minutes' => $expectedMinutes,
                'productivity' => $productivity,
            ];
        });

        return view(
            'dashboard.housekeeping.reports.productivity',
            compact(
                'date',
                'reports',
                'departureMinutes',
                'stayMinutes'
            )
        );
    }
}