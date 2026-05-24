<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\HolidayRequest;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

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
        // $request->validate([
        //     'year' => 'required|digits:4',
        //     'employee_id' => 'nullable|exists:users,id',
            
        // ]);

        $request->validate([
            'year' => 'required|digits:4',
            'month' => 'nullable|integer|min:1|max:12',
            'employee_id' => 'nullable|exists:users,id',
        ]);

            $query = User::with(['department', 'role', 'holidayRequests' => function ($q) use ($request) {
                $q->whereYear('start_date', $request->year)
                ->where('status', 'approved');

                if ($request->month) {
                    $q->whereMonth('start_date', $request->month);
                }

                $q->orderBy('start_date');
            }])
            ->whereHas('holidayRequests', function ($q) use ($request) {
                $q->whereYear('start_date', $request->year)
                ->where('status', 'approved');

                if ($request->month) {
                    $q->whereMonth('start_date', $request->month);
                }
            });

        

        if ($request->employee_id) {
            $query->where('id', $request->employee_id);
        }

        $employees = $query->orderBy('name')->get();

        // $pdf = Pdf::loadView('dashboard.manager.reports.holiday-pdf', [
        //     'employees' => $employees,
        //     'year' => $request->year,
        // ])->setPaper('a4', 'landscape');
        $pdf = Pdf::loadView('dashboard.manager.reports.holiday-pdf', [
            'employees' => $employees,
            'year' => $request->year,
            'month' => $request->month,
        ])->setPaper('a4', 'portrait');

        $employees = $query->orderBy('name')->get();

$pdf = Pdf::loadView('dashboard.manager.reports.holiday-pdf', [
    'employees' => $employees,
    'year' => $request->year,
    'month' => $request->month,
        ])->setPaper('a4', 'landscape');

        $fileName = 'holiday-request-report-' . $request->year;

        if ($request->month) {
            $fileName .= '-' . str_pad($request->month, 2, '0', STR_PAD_LEFT);
        }

        $fileName .= '.pdf';

        return $pdf->download($fileName);
        // return $pdf->download('holiday-request-report-' . $request->year . '.pdf');
    }
}