<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\HolidayRequest;
use App\Models\MaintenanceJob;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ManagerReportController extends Controller
{
    public function holidayReportForm()
    {
        $hotelId = auth()->user()->hotel_id;

        $employees = User::with('department')
            ->where('hotel_id', $hotelId)
            ->orderBy('name')
            ->get();

        return view('dashboard.manager.reports.holiday-form', compact('employees'));
    }

    public function generateHolidayPdf(Request $request)
    {
        $hotelId = auth()->user()->hotel_id;

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
            ->where('hotel_id', $hotelId)
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

    public function maintenanceReport(Request $request)
    {
        $hotelId = auth()->user()->hotel_id;

        $query = MaintenanceJob::with(['reporter', 'assignedUser', 'department'])
            ->where('hotel_id', $hotelId);

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

    public function maintenancePdf(Request $request)
    {
        $hotelId = auth()->user()->hotel_id;

        $query = MaintenanceJob::with([
                'reportedBy',
                'assignedTo',
                'department',
            ])
            ->where('hotel_id', $hotelId);

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