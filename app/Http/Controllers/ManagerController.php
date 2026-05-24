<?php

namespace App\Http\Controllers;
    use App\Models\User;
use App\Models\Department;
use App\Models\MaintenanceJob;
use App\Models\News;
use App\Models\Complaint;
use App\Models\HolidayRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;
// use App\Models\HolidayRequest;

class ManagerController extends Controller
{
    //
    public function managerDashboard()
{
    $totalEmployees = User::count();

    $activeEmployees = User::where('status', 'active')->count();

    $pendingMaintenance = MaintenanceJob::where('status', 'pending')->count();

    $inProgressMaintenance = MaintenanceJob::where('status', 'in_progress')->count();

    $completedMaintenance = MaintenanceJob::where('status', 'completed')->count();

    $pendingComplaints = Complaint::where('status', 'pending')->count();

    $resolvedComplaints = Complaint::where('status', 'resolved')->count();

    $activeNews = News::where('status', 'active')->count();

    $latestMaintenance = MaintenanceJob::with(['department', 'reporter', 'assignedUser'])
        ->latest()
        ->take(5)
        ->get();

    $latestComplaints = Complaint::latest()
        ->take(5)
        ->get();

    $departmentData = Department::withCount('users')->get();

    $departmentNames = $departmentData->pluck('name');
    $departmentCounts = $departmentData->pluck('users_count');

    return view('dashboard.manager.index', compact(
        'totalEmployees',
        'activeEmployees',
        'pendingMaintenance',
        'inProgressMaintenance',
        'completedMaintenance',
        'pendingComplaints',
        'resolvedComplaints',
        'activeNews',
        'latestMaintenance',
        'latestComplaints',
        'departmentNames',
        'departmentCounts'
    ));
}

public function maintenance()
{
    $jobs = MaintenanceJob::with(['department', 'reporter', 'assignedUser'])
        ->latest()
        ->get();

    return view('dashboard.manager.maintenance.index', compact('jobs'));
}

public function complaints()
{
    $complaints = Complaint::latest()->get();

    return view('dashboard.manager.complaints.index', compact('complaints'));
}



public function updateComplaintStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:pending,in_progress,resolved,closed',
    ]);

    $complaint = Complaint::findOrFail($id);

    $complaint->update([
        'status' => $request->status,
        'handled_by' => auth()->id(),
    ]);

    return back()->with('success', 'Complaint status updated successfully.');
}


public function holidays()
{
    $holidayRequests = HolidayRequest::with(['user', 'department', 'approver'])
        ->latest()
        ->get();

    return view('dashboard.manager.holidays.index', compact('holidayRequests'));
}

public function updateHolidayStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:approved,rejected',
        'manager_note' => 'nullable|string|max:1000',
    ]);

    $holiday = HolidayRequest::findOrFail($id);

    $holiday->update([
        'status' => $request->status,
        'approved_by' => auth()->id(),
        'manager_note' => $request->manager_note,
    ]);

    return back()->with('success', 'Holiday request updated successfully.');
}

//Holiday calander
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

    return view('dashboard.manager.holidays.calendar', compact('events'));
}
}

