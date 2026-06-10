<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\HolidayRequest;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\MaintenanceJob;

class ManagerReportController extends Controller
{
    public function holidayReportForm()
    {
        $employees = User::with('department')
            ->whereHas('role', function ($query) {
                $query->where('name', 'staff');
            })
            ->orderBy('name')
            ->get();

        return view('dashboard.manager.reports.holiday-form', compact('employees'));
    }

public function generateHolidayPdf(Request $request)
{
    $request->validate([
        'year' => 'required|digits:4',
        'month' => 'nullable|integer|min:1|max:12',
        'employee_id' => 'nullable|exists:users,id',
    ]);

    $year = (int) $request->year;
    $month = $request->filled('month') ? (int) $request->month : null;

    $query = User::with([
        'department',
        'role',
        'holidayRequests' => function ($q) use ($year, $month) {
            $q->with('approver')
                ->whereYear('start_date', $year)
                ->where('status', 'approved');

            if ($month) {
                $q->whereMonth('start_date', $month);
            }

            $q->orderBy('start_date');
        }
    ])
    ->whereHas('holidayRequests', function ($q) use ($year, $month) {
        $q->whereYear('start_date', $year)
            ->where('status', 'approved');

        if ($month) {
            $q->whereMonth('start_date', $month);
        }
    });

    if ($request->filled('employee_id')) {
        $query->where('id', $request->employee_id);
    }

    $employees = $query->orderBy('name')->get();

    if ($employees->isEmpty()) {
        return back()->with(
            'error',
            'No approved holiday requests found for the selected filter.'
        );
    }

    $pdf = Pdf::loadView('dashboard.manager.reports.holiday-pdf', [
        'employees' => $employees,
        'year' => $year,
        'month' => $month,
    ])->setPaper('a4', 'portrait');

    $fileName = 'holiday-request-report-' . $year;

    if ($month) {
        $fileName .= '-' . str_pad($month, 2, '0', STR_PAD_LEFT);
    }

    return $pdf->download($fileName . '.pdf');
}

public function index()
{
    return view('dashboard.manager.reports.index');
}

//Maintanance report for manager
public function maintenanceReport(Request $request)
{
    $query = MaintenanceJob::with(['reporter', 'assignedUser', 'department']);

    if ($request->filled('date_from')) {
        $query->whereDate('created_at', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
        $query->whereDate('created_at', '<=', $request->date_to);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('priority')) {
        $query->where('priority', $request->priority);
    }

    $jobs = $query->latest()->get();

    $totalJobs = $jobs->count();
    $pendingJobs = $jobs->where('status', 'pending')->count();
    $inProgressJobs = $jobs->where('status', 'in_progress')->count();
    $completedJobs = $jobs->where('status', 'completed')->count();
    $urgentJobs = $jobs->where('priority', 'urgent')->count();

    return view('dashboard.manager.reports.maintenance', compact(
        'jobs',
        'totalJobs',
        'pendingJobs',
        'inProgressJobs',
        'completedJobs',
        'urgentJobs'
    ));
}


//maintanance report pdf

public function maintenancePdf(Request $request)
{
    $query = MaintenanceJob::with([
        'reportedBy',
        'assignedTo',
        'department'
    ]);

    if ($request->filled('date_from')) {
        $query->whereDate('created_at', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
        $query->whereDate('created_at', '<=', $request->date_to);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('priority')) {
        $query->where('priority', $request->priority);
    }

    $jobs = $query->latest()->get();

    $pdf = Pdf::loadView(
        'dashboard.manager.reports.pdf.maintenance',
        compact('jobs')
    );

    return $pdf->download('maintenance-report.pdf');
}

}