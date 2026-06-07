<?php

namespace App\Http\Controllers\Housekeeping;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\RotaShift;
use App\Models\User;
use Illuminate\Http\Request;

class StaffWorkingTodayController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->date ?? now()->toDateString();

        $hkDepartmentIds = Department::whereRaw('LOWER(name) IN (?, ?, ?)', [
            'housekeeping',
            'house keeping',
            'hk',
        ])->pluck('id');

        $workingShifts = RotaShift::with('user')
            ->whereDate('shift_date', $date)
            ->whereIn('department_id', $hkDepartmentIds)
            ->where('status', 'published')
            ->orderBy('shift_type')
            ->get();

        $availableExtraStaff = User::whereIn('department_id', $hkDepartmentIds)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('dashboard.housekeeping.staff-working-today.index', compact(
            'date',
            'workingShifts',
            'availableExtraStaff'
        ));
    }

    public function addExtraStaff(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'shift_date' => 'required|date',
            'shift_type' => 'required|in:morning,evening,night,split',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
        ]);

        $user = User::findOrFail($request->user_id);

        RotaShift::updateOrCreate(
            [
                'user_id' => $user->id,
                'shift_date' => $request->shift_date,
            ],
            [
                'department_id' => $user->department_id,
                'shift_type' => $request->shift_type,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'break_minutes' => 0,
                'status' => 'published',
                'notes' => 'Added extra by HK Supervisor',
            ]
        );

        return back()->with('success', 'Extra staff added successfully.');
    }

    public function markUnavailable(Request $request, RotaShift $rotaShift)
    {
        $request->validate([
            'shift_type' => 'required|in:sick,day_off,holiday',
        ]);

        $rotaShift->update([
            'shift_type' => $request->shift_type,
            'notes' => 'Updated by HK Supervisor',
        ]);

        return back()->with('success', 'Staff status updated successfully.');
    }
}