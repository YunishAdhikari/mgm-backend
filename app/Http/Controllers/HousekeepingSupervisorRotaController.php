<?php

namespace App\Http\Controllers;

use App\Models\HolidayRequest;
use App\Models\User;
use App\Models\RotaShift;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HousekeepingSupervisorRotaController extends Controller
{
    public function index(Request $request)
    {
        $selectedDate = $request->date ?? today()->toDateString();

        $date = Carbon::parse($selectedDate);

        $hkStaff = User::with('department')
            ->whereHas('department', function ($query) {
                $query->whereIn('name', [
                    'Housekeeping',
                    'HK',
                    'House Keeping',
                ]);
            })
            ->orderBy('name')
            ->get();

        $existingShifts = RotaShift::whereDate('shift_date', $date)
            ->get()
            ->keyBy('user_id');

        return view('dashboard.housekeeping.rota.index', compact(
            'selectedDate',
            'hkStaff',
            'existingShifts'
        ));
    }

    public function saveDraft(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'staff_ids' => 'required|array',
            'staff_ids.*' => 'exists:users,id',
            'shift_type' => 'required|in:morning,evening,night,split,day_off,holiday,sick',
            'start_time' => 'nullable|required_unless:shift_type,day_off,holiday,sick',
            'end_time' => 'nullable|required_unless:shift_type,day_off,holiday,sick',
        ]);

        foreach ($request->staff_ids as $staffId) {
            RotaShift::updateOrCreate(
                [
                    'user_id' => $staffId,
                    'shift_date' => $request->date,
                ],
                [
                    'shift_type' => $request->shift_type,
                    'start_time' => in_array($request->shift_type, ['day_off', 'holiday', 'sick'])
                        ? null
                        : $request->start_time,
                    'end_time' => in_array($request->shift_type, ['day_off', 'holiday', 'sick'])
                        ? null
                        : $request->end_time,
                    'status' => 'draft',
                    'created_by' => auth()->id(),
                ]
            );
        }

        return back()->with('success', 'HK rota saved as draft successfully.');
    }

    public function removeDraft(RotaShift $shift)
{
    if ($shift->status !== 'draft') {
        return back()->with('error', 'Only draft rota shifts can be removed.');
    }

    $shift->delete();

    return back()->with('success', 'Staff removed from draft rota successfully.');
}


public function view(Request $request)
{
    $weekStart = $request->week_start
        ? \Carbon\Carbon::parse($request->week_start)->startOfWeek()
        : today()->startOfWeek();

    $weekEnd = $weekStart->copy()->endOfWeek();

    $weekDays = collect();

    for ($i = 0; $i < 7; $i++) {
        $weekDays->push($weekStart->copy()->addDays($i));
    }

    $hkStaff = User::with('department')
        ->whereHas('department', function ($query) {
            $query->whereIn('name', [
                'Housekeeping',
                'HK',
                'House Keeping',
            ]);
        })
        ->orderBy('name')
        ->get();

    $shifts = RotaShift::whereBetween('shift_date', [
            $weekStart->toDateString(),
            $weekEnd->toDateString(),
        ])
        ->get()
        ->groupBy(function ($shift) {
            return $shift->user_id . '_' . $shift->shift_date;
        });

    $stats = [
        'staff_count' => $hkStaff->count(),
        'draft' => RotaShift::whereBetween('shift_date', [
                $weekStart->toDateString(),
                $weekEnd->toDateString(),
            ])
            ->where('status', 'draft')
            ->count(),

        'published' => RotaShift::whereBetween('shift_date', [
                $weekStart->toDateString(),
                $weekEnd->toDateString(),
            ])
            ->where('status', 'published')
            ->count(),
    ];

    return view('dashboard.housekeeping.rota.view', compact(
        'weekStart',
        'weekEnd',
        'weekDays',
        'hkStaff',
        'shifts',
        'stats'
    ));
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

        return view('dashboard.housekeeping.rota.holiday-calendar', compact('events'));
    }
}