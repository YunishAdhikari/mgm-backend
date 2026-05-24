<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Department;
use App\Models\RotaShift;
use Barryvdh\DomPDF\Facade\Pdf;

class ManagerAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = AttendanceLog::with('user')->latest();

        if ($request->filled('date')) {
            $query->whereDate('attendance_date', $request->date);
        }

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('dashboard.manager.attendance.index', compact('logs'));
    }

    public function update(Request $request, AttendanceLog $attendanceLog)
    {
        $request->validate([
            'attendance_date' => 'required|date',
            'clock_in_at' => 'nullable',
            'clock_out_at' => 'nullable',
            'status' => 'required|in:clocked_in,clocked_out',
        ]);

        $clockIn = $request->clock_in_at
            ? Carbon::parse($request->attendance_date . ' ' . $request->clock_in_at)
            : null;

        $clockOut = $request->clock_out_at
            ? Carbon::parse($request->attendance_date . ' ' . $request->clock_out_at)
            : null;

        $attendanceLog->update([
            'attendance_date' => $request->attendance_date,
            'clock_in_at' => $clockIn,
            'clock_out_at' => $clockOut,
            'status' => $request->status,
        ]);

        return back()->with('success', 'Attendance updated successfully.');
    }

    public function destroy(AttendanceLog $attendanceLog)
    {
        $attendanceLog->delete();

        return back()->with('success', 'Attendance record removed successfully.');
    }



    //Monthly report
    public function monthlyReportForm()
{
    $users = User::where('status', 'active')
        ->orderBy('name')
        ->get();

    $departments = Department::orderBy('name')->get();

    return view('dashboard.manager.attendance.monthly-form', compact(
        'users',
        'departments'
    ));
}

public function monthlyReportPdf(Request $request)
{
    $request->validate([
        'report_type' => 'required|in:individual,all',
        'month' => 'required|date_format:Y-m',
        'user_id' => 'nullable|exists:users,id',
        'department_id' => 'nullable|exists:departments,id',
    ]);

    $monthStart = Carbon::parse($request->month . '-01')->startOfMonth();
    $monthEnd = $monthStart->copy()->endOfMonth();
    $today = now()->startOfDay();

    $usersQuery = User::with(['role', 'department'])
        ->where('status', 'active')
        ->whereHas('role', function ($q) {
            $q->whereRaw('LOWER(name) != ?', ['admin']);
        });

    if ($request->report_type === 'individual') {
        $usersQuery->where('id', $request->user_id);
    }

    if ($request->filled('department_id')) {
        $usersQuery->where('department_id', $request->department_id);
    }

    $users = $usersQuery->orderBy('name')->get();

    $reports = [];

    foreach ($users as $user) {
        $attendanceLogs = AttendanceLog::where('user_id', $user->id)
            ->whereBetween('attendance_date', [
                $monthStart->format('Y-m-d'),
                $monthEnd->format('Y-m-d'),
            ])
            ->get()
            ->keyBy(function ($log) {
                return Carbon::parse($log->attendance_date)->format('Y-m-d');
            });

        $rotaShifts = RotaShift::where('user_id', $user->id)
            ->whereBetween('shift_date', [
                $monthStart->format('Y-m-d'),
                $monthEnd->format('Y-m-d'),
            ])
            ->get()
            ->keyBy(function ($shift) {
                return Carbon::parse($shift->shift_date)->format('Y-m-d');
            });

        $days = [];
        $actualHours = 0;
        $forecastHours = 0;

        for ($date = $monthStart->copy(); $date <= $monthEnd; $date->addDay()) {
            $key = $date->format('Y-m-d');

            $log = $attendanceLogs->get($key);
            $shift = $rotaShifts->get($key);

            $timeIn = '';
            $timeOut = '';
            $hours = '';
            $initials = '';

            if ($date->lessThanOrEqualTo($today)) {
                if ($log && $log->clock_in_at && $log->clock_out_at) {
                    $timeIn = Carbon::parse($log->clock_in_at)->format('H:i');
                    $timeOut = Carbon::parse($log->clock_out_at)->format('H:i');

                    // $calculatedHours = round(
                    //     Carbon::parse($log->clock_in_at)
                    //         ->diffInMinutes(Carbon::parse($log->clock_out_at)) / 60,
                    //     2
                    // );
                    $clockIn = Carbon::parse($log->clock_in_at);
                        $clockOut = Carbon::parse($log->clock_out_at);

                        if ($clockOut->lessThan($clockIn)) {
                            $clockOut->addDay();
                        }

                        $calculatedHours = round(
                            $clockIn->diffInMinutes($clockOut) / 60,
                            2
                        );

                    $hours = $calculatedHours;
                    $actualHours += $calculatedHours;
                    $initials = $this->getInitials($user->name);
                }
            } else {
                if ($shift && $shift->start_time && $shift->end_time) {
                    $timeIn = Carbon::parse($shift->start_time)->format('H:i');
                    $timeOut = Carbon::parse($shift->end_time)->format('H:i');

                    // $calculatedHours = round(
                    //     Carbon::parse($shift->start_time)
                    //         ->diffInMinutes(Carbon::parse($shift->end_time)) / 60,
                    //     2
                    // );
                    $shiftStart = Carbon::parse($shift->start_time);
                        $shiftEnd = Carbon::parse($shift->end_time);

                        if ($shiftEnd->lessThan($shiftStart)) {
                            $shiftEnd->addDay();
                        }

                        $calculatedHours = round(
                            $shiftStart->diffInMinutes($shiftEnd) / 60,
                            2
                        );

                    $hours = $calculatedHours;
                    $forecastHours += $calculatedHours;
                    $initials = $this->getInitials($user->name);
                }
            }

            if ($hours !== '') {
                $days[] = [
                    'date' => $date->format('d/m/Y'),
                    'time_in' => $timeIn,
                    'time_out' => $timeOut,
                    'initials' => $initials,
                    'hours' => $hours,
                ];
            }
        }

        $reports[] = [
            'user' => $user,
            'days' => $days,
            'actual_hours' => round($actualHours, 2),
            'forecast_hours' => round($forecastHours, 2),
            'projected_hours' => round($actualHours + $forecastHours, 2),
        ];
    }

    $pdf = Pdf::loadView('dashboard.manager.attendance.monthly-pdf', compact(
        'reports',
        'monthStart',
        'monthEnd'
    ))->setPaper('a4', 'portrait');

    return $pdf->download('monthly-attendance-' . $monthStart->format('Y-m') . '.pdf');
}

private function getInitials($name)
{
    return collect(explode(' ', $name))
        ->filter()
        ->map(function ($part) {
            return strtoupper(substr($part, 0, 1));
        })
        ->join('');
}
}