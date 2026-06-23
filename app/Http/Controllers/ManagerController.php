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

class ManagerController extends Controller
{
    public function managerDashboard()
    {
        $manager = auth()->user();
        $hotelId = $manager->hotel_id;

        $totalEmployees = User::where('hotel_id', $hotelId)->count();

        $activeEmployees = User::where('hotel_id', $hotelId)
            ->where('status', 'active')
            ->count();

        $pendingMaintenance = MaintenanceJob::where('hotel_id', $hotelId)
            ->where('status', 'pending')
            ->count();

        $inProgressMaintenance = MaintenanceJob::where('hotel_id', $hotelId)
            ->where('status', 'in_progress')
            ->count();

        $completedMaintenance = MaintenanceJob::where('hotel_id', $hotelId)
            ->where('status', 'completed')
            ->count();

        $pendingComplaints = Complaint::where('hotel_id', $hotelId)
    ->where('status', 'pending')
    ->count();

$resolvedComplaints = Complaint::where('hotel_id', $hotelId)
    ->where('status', 'resolved')
    ->count();

$latestComplaints = Complaint::with(['hotel', 'creator', 'handler'])
    ->where('hotel_id', $hotelId)
    ->latest()
    ->take(5)
    ->get();

        $activeNews = News::where('status', 'active')->count();

        $latestMaintenance = MaintenanceJob::with(['hotel', 'department', 'reporter', 'assignedUser'])
            ->where('hotel_id', $hotelId)
            ->latest()
            ->take(5)
            ->get();

    

        $departmentData = Department::where(function ($query) use ($hotelId) {
                $query->where('hotel_id', $hotelId)
                    ->orWhereNull('hotel_id');
            })
            ->withCount(['users' => function ($query) use ($hotelId) {
                $query->where('hotel_id', $hotelId);
            }])
            ->get();

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
        $hotelId = auth()->user()->hotel_id;

        $jobs = MaintenanceJob::with(['hotel', 'department', 'reporter', 'assignedUser'])
            ->where('hotel_id', $hotelId)
            ->latest()
            ->get();

        return view('dashboard.manager.maintenance.index', compact('jobs'));
    }

 public function complaints()
{
    $hotelId = auth()->user()->hotel_id;

    $complaints = Complaint::with([
            'hotel',
            'creator',
            'handler',
        ])
        ->where('hotel_id', $hotelId)
        ->latest()
        ->get();

    return view('dashboard.manager.complaints.index', compact('complaints'));
}

    public function updateComplaintStatus(Request $request, $id)
    {
        $hotelId = auth()->user()->hotel_id;

        $request->validate([
            'status' => 'required|in:pending,in_progress,resolved,closed',
        ]);

        $complaint = Complaint::whereHas('user', function ($query) use ($hotelId) {
                $query->where('hotel_id', $hotelId);
            })
            ->findOrFail($id);

        $complaint->update([
            'status' => $request->status,
            'handled_by' => auth()->id(),
        ]);

        return back()->with('success', 'Complaint status updated successfully.');
    }

    public function holidays()
    {
        $hotelId = auth()->user()->hotel_id;

        $holidayRequests = HolidayRequest::with(['user', 'department', 'approver'])
            ->whereHas('user', function ($query) use ($hotelId) {
                $query->where('hotel_id', $hotelId);
            })
            ->latest()
            ->get();

        return view('dashboard.manager.holidays.index', compact('holidayRequests'));
    }

    public function updateHolidayStatus(Request $request, $id)
    {
        $hotelId = auth()->user()->hotel_id;

        $request->validate([
            'status' => 'required|in:approved,rejected',
            'manager_note' => 'nullable|string|max:1000',
        ]);

        $holiday = HolidayRequest::whereHas('user', function ($query) use ($hotelId) {
                $query->where('hotel_id', $hotelId);
            })
            ->findOrFail($id);

        $holiday->update([
            'status' => $request->status,
            'approved_by' => auth()->id(),
            'manager_note' => $request->manager_note,
        ]);

        return back()->with('success', 'Holiday request updated successfully.');
    }

    public function holidayCalendar()
    {
        $hotelId = auth()->user()->hotel_id;

        $holidayRequests = HolidayRequest::with(['user', 'department'])
            ->whereHas('user', function ($query) use ($hotelId) {
                $query->where('hotel_id', $hotelId);
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

        return view('dashboard.manager.holidays.calendar', compact('events'));
    }
}