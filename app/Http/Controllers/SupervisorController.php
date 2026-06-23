<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RotaShift;
use App\Models\User;
use App\Models\HolidayRequest;
use Carbon\Carbon;

class SupervisorController extends Controller
{
    public function dashboard()
    {
        return view('dashboard.supervisor.index');
    }

    public function holidayCalendar()
    {
        $supervisor = auth()->user();
        $hotelId = $supervisor->hotel_id;

        $holidayRequests = HolidayRequest::with(['user', 'department'])
            ->whereHas('user', function ($query) use ($hotelId, $supervisor) {
                $query->where('hotel_id', $hotelId)
                    ->where('department_id', $supervisor->department_id);
            })
            ->whereIn('status', ['approved', 'pending'])
            ->get();

        $events = $holidayRequests->map(function ($holiday) {
            return [
                'title' => ($holiday->user->name ?? 'Staff') . ' - ' . ucfirst($holiday->status),
                'start' => $holiday->start_date,
                'end' => Carbon::parse($holiday->end_date)->addDay()->format('Y-m-d'),
                'color' => $holiday->status === 'approved' ? '#22c55e' : '#f59e0b',
                'extendedProps' => [
                    'employee' => $holiday->user->name ?? 'N/A',
                    'department' => $holiday->department->name ?? 'N/A',
                    'status' => ucfirst($holiday->status),
                    'reason' => $holiday->reason ?? 'No reason provided',
                    'total_days' => $holiday->total_days,
                ],
            ];
        });

        return view('dashboard.supervisor.holiday-calendar', compact('events'));
    }

    public function view()
    {
        $supervisor = auth()->user();
        $hotelId = $supervisor->hotel_id;

        $employees = User::where('hotel_id', $hotelId)
            ->where('department_id', $supervisor->department_id)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $weekStart = request('week_start')
            ? Carbon::parse(request('week_start'))->startOfWeek()
            : now()->startOfWeek();

        $weekDates = collect(range(0, 6))->map(function ($day) use ($weekStart) {
            return $weekStart->copy()->addDays($day);
        });

        $shifts = RotaShift::with(['user', 'department'])
            ->whereHas('user', function ($query) use ($hotelId) {
                $query->where('hotel_id', $hotelId);
            })
            ->where('department_id', $supervisor->department_id)
            ->whereBetween('shift_date', [
                $weekStart->copy()->format('Y-m-d'),
                $weekStart->copy()->addDays(6)->format('Y-m-d'),
            ])
            ->orderBy('start_time')
            ->get();

        return view('dashboard.supervisor.rota.view', compact(
            'supervisor',
            'employees',
            'weekDates',
            'weekStart',
            'shifts'
        ));
    }

    public function storeRota(Request $request)
    {
        $supervisor = auth()->user();
        $hotelId = $supervisor->hotel_id;

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'shift_date' => 'required|date',
            'shift_type' => 'required|in:morning,evening,night,split,day_off,holiday,sick',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'notes' => 'nullable|string',
        ]);

        $employee = User::where('hotel_id', $hotelId)
            ->where('department_id', $supervisor->department_id)
            ->where('status', 'active')
            ->findOrFail($request->user_id);

        RotaShift::create([
            'user_id' => $employee->id,
            'department_id' => $supervisor->department_id,
            'shift_date' => $request->shift_date,
            'shift_type' => $request->shift_type,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'break_minutes' => $request->break_minutes ?? 0,
            'status' => 'draft',
            'notes' => $request->notes,
            'created_by' => $supervisor->id,
        ]);

        return back()->with('success', 'Shift added successfully.');
    }
}