<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RotaShift;
use App\Models\User;
use App\Models\HolidayRequest;

class SupervisorController extends Controller
{
    public function dashboard()
    {
        return view('dashboard.supervisor.index');
    }

    public function holidayCalendar()
    {
        $holidayRequests = HolidayRequest::with(['user', 'department'])
            ->whereIn('status', ['approved', 'pending'])
            ->get();

        $events = $holidayRequests->map(function ($holiday) {
            return [
                'title' => ($holiday->user->name ?? 'Staff') . ' - ' . ucfirst($holiday->status),
                'start' => $holiday->start_date,
                'end' => \Carbon\Carbon::parse($holiday->end_date)->addDay()->format('Y-m-d'),
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

    //view
    public function view()
{
    $supervisor = auth()->user();

    $employees = User::where(
        'department_id',
        $supervisor->department_id
    )->get();

    $weekStart = request('week_start')
        ? \Carbon\Carbon::parse(request('week_start'))->startOfWeek()
        : now()->startOfWeek();

    $weekDates = collect(range(0, 6))->map(function ($day) use ($weekStart) {
        return $weekStart->copy()->addDays($day);
    });

    $rotas = RotaShift::whereBetween('shift_date', [
        $weekStart->copy()->startOfDay(),
        $weekStart->copy()->endOfWeek(),
    ])->get();

 $shifts = RotaShift::where('department_id', $supervisor->department_id)
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


//store rota as a supervisor
public function storeRota(Request $request)
{
    $supervisor = auth()->user();

    $request->validate([
        'user_id' => 'required',
        'shift_date' => 'required|date',
        'shift_type' => 'required',
    ]);

    RotaShift::create([
        'user_id' => $request->user_id,
        'department_id' => $supervisor->department_id,
        'shift_date' => $request->shift_date,
        'shift_type' => $request->shift_type,
        'start_time' => $request->start_time,
        'end_time' => $request->end_time,
        'notes' => $request->notes,
        'created_by' => $supervisor->id,
    ]);

    return back()->with('success', 'Shift added successfully.');
}
}