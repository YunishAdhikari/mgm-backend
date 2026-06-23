<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RotaShift;
use App\Models\Department;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RotaController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $role = strtolower($user->role->name ?? '');
        $hotelId = $user->hotel_id;

        if ($role === 'supervisor') {
            $employees = User::with(['role', 'department'])
                ->where('hotel_id', $hotelId)
                ->where('status', 'active')
                ->where('department_id', $user->department_id)
                ->orderBy('name')
                ->get();

            $departments = Department::where('hotel_id', $hotelId)
                ->where('id', $user->department_id)
                ->get();

            $shifts = RotaShift::with(['user', 'department'])
                ->whereHas('user', function ($q) use ($hotelId) {
                    $q->where('hotel_id', $hotelId);
                })
                ->where('department_id', $user->department_id)
                ->orderBy('shift_date', 'desc')
                ->orderBy('start_time')
                ->get();

            return view('dashboard.supervisor.rota.index', compact(
                'employees',
                'departments',
                'shifts'
            ));
        }

        $employees = User::with(['role', 'department'])
            ->where('hotel_id', $hotelId)
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $departments = Department::where('hotel_id', $hotelId)
            ->orderBy('name')
            ->get();

        $shifts = RotaShift::with(['user', 'department'])
            ->whereHas('user', function ($q) use ($hotelId) {
                $q->where('hotel_id', $hotelId);
            })
            ->orderBy('shift_date', 'desc')
            ->orderBy('start_time')
            ->get();

        return view('dashboard.manager.rota.index', compact(
            'employees',
            'departments',
            'shifts'
        ));
    }

    public function store(Request $request)
    {
        $authUser = auth()->user();
        $role = strtolower($authUser->role->name ?? '');
        $hotelId = $authUser->hotel_id;

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'department_id' => 'nullable|exists:departments,id',
            'shift_date' => 'required|date',
            'shift_type' => 'required|in:morning,evening,night,split,day_off,holiday,sick',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'break_minutes' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
            'split_start_time_1' => 'nullable',
            'split_end_time_1' => 'nullable',
            'split_start_time_2' => 'nullable',
            'split_end_time_2' => 'nullable',
        ]);

        $employee = User::where('hotel_id', $hotelId)
            ->findOrFail($request->user_id);

        $departmentId = $role === 'supervisor'
            ? $authUser->department_id
            : ($request->department_id ?? $employee->department_id);

        if ($role === 'supervisor' && $employee->department_id != $authUser->department_id) {
            abort(403);
        }

        if ($departmentId) {
            Department::where('hotel_id', $hotelId)
                ->where('id', $departmentId)
                ->firstOrFail();
        }

        if ($request->shift_type === 'split') {
            $request->validate([
                'split_start_time_1' => 'required',
                'split_end_time_1' => 'required',
                'split_start_time_2' => 'required',
                'split_end_time_2' => 'required',
            ]);

            RotaShift::create([
                'user_id' => $employee->id,
                'department_id' => $departmentId,
                'shift_date' => $request->shift_date,
                'shift_type' => 'split',
                'start_time' => $request->split_start_time_1,
                'end_time' => $request->split_end_time_1,
                'break_minutes' => $request->break_minutes ?? 0,
                'status' => 'draft',
                'notes' => $request->notes,
            ]);

            RotaShift::create([
                'user_id' => $employee->id,
                'department_id' => $departmentId,
                'shift_date' => $request->shift_date,
                'shift_type' => 'split',
                'start_time' => $request->split_start_time_2,
                'end_time' => $request->split_end_time_2,
                'break_minutes' => $request->break_minutes ?? 0,
                'status' => 'draft',
                'notes' => $request->notes,
            ]);
        } else {
            RotaShift::create([
                'user_id' => $employee->id,
                'department_id' => $departmentId,
                'shift_date' => $request->shift_date,
                'shift_type' => $request->shift_type,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'break_minutes' => $request->break_minutes ?? 0,
                'status' => 'draft',
                'notes' => $request->notes,
            ]);
        }

        return back()->with('success', 'Rota shift added successfully.');
    }

    public function destroy($id)
    {
        $hotelId = auth()->user()->hotel_id;

        $shift = RotaShift::whereHas('user', function ($q) use ($hotelId) {
                $q->where('hotel_id', $hotelId);
            })
            ->findOrFail($id);

        $shift->delete();

        return back()->with('success', 'Shift deleted successfully.');
    }

    public function publish(Request $request)
    {
        $hotelId = auth()->user()->hotel_id;

        $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        RotaShift::whereHas('user', function ($q) use ($hotelId) {
                $q->where('hotel_id', $hotelId);
            })
            ->whereBetween('shift_date', [
                $request->from_date,
                $request->to_date,
            ])
            ->update([
                'status' => 'published',
            ]);

        if (function_exists('logActivity')) {
            logActivity('Published Rota', 'Rota', 'Rota published');
        }

        return back()->with('success', 'Rota published successfully.');
    }

    public function managerView()
    {
        $hotelId = auth()->user()->hotel_id;

        $weekStart = request('week_start')
            ? Carbon::parse(request('week_start'))->startOfWeek()
            : now()->startOfWeek();

        $weekDates = collect(range(0, 6))->map(function ($day) use ($weekStart) {
            return $weekStart->copy()->addDays($day);
        });

        $departments = Department::where('hotel_id', $hotelId)
            ->with(['users' => function ($query) use ($hotelId) {
                $query->where('hotel_id', $hotelId)
                    ->where('status', 'active')
                    ->whereHas('role', function ($roleQuery) {
                        $roleQuery->whereRaw('LOWER(name) != ?', ['admin']);
                    })
                    ->orderBy('name');
            }])
            ->orderBy('name')
            ->get();

        $shifts = RotaShift::with(['user', 'department'])
            ->whereHas('user', function ($q) use ($hotelId) {
                $q->where('hotel_id', $hotelId);
            })
            ->whereBetween('shift_date', [
                $weekStart->copy()->format('Y-m-d'),
                $weekStart->copy()->addDays(6)->format('Y-m-d'),
            ])
            ->orderBy('start_time')
            ->get();

        return view('dashboard.manager.rota.view', compact(
            'departments',
            'weekDates',
            'weekStart',
            'shifts'
        ));
    }

    public function managerRotaPdf()
    {
        $hotelId = auth()->user()->hotel_id;

        $weekStart = request('week_start')
            ? Carbon::parse(request('week_start'))->startOfWeek()
            : now()->startOfWeek();

        $weekDates = collect(range(0, 6))->map(function ($day) use ($weekStart) {
            return $weekStart->copy()->addDays($day);
        });

        $departments = Department::where('hotel_id', $hotelId)
            ->with(['users' => function ($query) use ($hotelId) {
                $query->where('hotel_id', $hotelId)
                    ->where('status', 'active')
                    ->whereHas('role', function ($roleQuery) {
                        $roleQuery->whereRaw('LOWER(name) != ?', ['admin']);
                    })
                    ->orderBy('name');
            }])
            ->orderBy('name')
            ->get();

        $shifts = RotaShift::with(['user', 'department'])
            ->whereHas('user', function ($q) use ($hotelId) {
                $q->where('hotel_id', $hotelId);
            })
            ->whereBetween('shift_date', [
                $weekStart->copy()->format('Y-m-d'),
                $weekStart->copy()->addDays(6)->format('Y-m-d'),
            ])
            ->orderBy('start_time')
            ->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('dashboard.manager.rota.pdf', compact(
            'departments',
            'weekDates',
            'weekStart',
            'shifts'
        ))->setPaper('a4', 'portrait');

        return $pdf->download('weekly-rota-' . $weekStart->format('Y-m-d') . '.pdf');
    }
}