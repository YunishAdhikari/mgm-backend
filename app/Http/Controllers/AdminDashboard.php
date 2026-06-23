<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Hotel;
use App\Models\Department;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\MaintenanceJob;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\EmployeeCredentialsMail;

class AdminDashboard extends Controller
{
    public function dashboard()
    {
        $totalHotels = Hotel::count();
        $activeHotels = Hotel::where('is_active', true)->count();

        $totalEmployees = User::count();
        $activeEmployees = User::where('status', 'active')->count();

        $openMaintenance = MaintenanceJob::where('status', '!=', 'completed')->count();
        $pendingJobs = MaintenanceJob::where('status', 'pending')->count();
        $inProgressJobs = MaintenanceJob::where('status', 'in_progress')->count();
        $completedJobs = MaintenanceJob::where('status', 'completed')->count();
        $cancelledJobs = MaintenanceJob::where('status', 'cancelled')->count();

        $activeNews = News::where('status', 'active')->count();

        $statusChart = [
            $pendingJobs,
            $inProgressJobs,
            $completedJobs,
            $cancelledJobs,
        ];

        $priorityChart = [
            MaintenanceJob::where('priority', 'low')->count(),
            MaintenanceJob::where('priority', 'medium')->count(),
            MaintenanceJob::where('priority', 'high')->count(),
            MaintenanceJob::where('priority', 'urgent')->count(),
        ];

        $departmentData = Department::withCount('users')->get();
        $departmentNames = $departmentData->pluck('name');
        $departmentCounts = $departmentData->pluck('users_count');

        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->take(10)
            ->get();

        $totalActivitiesToday = ActivityLog::whereDate('created_at', today())->count();
        $totalMaintenanceJobs = MaintenanceJob::count();

        return view('dashboard.admin.index', compact(
            'totalHotels',
            'activeHotels',
            'recentActivities',
            'totalEmployees',
            'activeEmployees',
            'openMaintenance',
            'activeNews',
            'pendingJobs',
            'inProgressJobs',
            'completedJobs',
            'cancelledJobs',
            'departmentNames',
            'departmentCounts',
            'totalActivitiesToday',
            'totalMaintenanceJobs',
            'statusChart',
            'priorityChart'
        ));
    }

public function index(Request $request)
{
    $search = $request->search;

    $users = User::with(['hotel', 'role', 'department'])
        ->when($search, function ($query) use ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('employee_code', 'like', "%{$search}%")
                    ->orWhere('job_title', 'like', "%{$search}%")
                    ->orWhereHas('hotel', function ($hotelQuery) use ($search) {
                        $hotelQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('code', 'like', "%{$search}%");
                    })
                    ->orWhereHas('role', function ($roleQuery) use ($search) {
                        $roleQuery->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('department', function ($departmentQuery) use ($search) {
                        $departmentQuery->where('name', 'like', "%{$search}%");
                    });
            });
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

    return view('dashboard.admin.showemployee', compact('users', 'search'));
}

    public function addemployeepage()
    {
        return redirect()->route('addemp');
    }

    public function create()
    {
        $hotels = Hotel::where('is_active', true)->orderBy('name')->get();
        $roles = Role::orderBy('name')->get();
        $departments = Department::orderBy('name')->get();

        return view('dashboard.admin.addemp', compact('hotels', 'roles', 'departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'hotel_id' => 'nullable|exists:hotels,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:50',
            'employee_code' => 'nullable|string|max:100|unique:users,employee_code',
            'job_title' => 'nullable|string|max:150',
            'password' => 'required|min:6',
            'role_id' => 'required|exists:roles,id',
            'department_id' => 'nullable|exists:departments,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('users', 'public');
        }

        $plainPassword = $request->password;

        $user = User::create([
            'hotel_id' => $request->hotel_id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'employee_code' => $request->employee_code,
            'job_title' => $request->job_title,
            'password' => Hash::make($plainPassword),
            'role_id' => $request->role_id,
            'image' => $imagePath,
            'department_id' => $request->department_id,
            'status' => 'active',
        ]);

        try {
            Mail::to($user->email)
                ->send(new EmployeeCredentialsMail($user, $plainPassword));
        } catch (\Exception $e) {
            Log::error('Employee credential email failed: ' . $e->getMessage());

            return redirect()
                ->route('dashboard.admin.showemp')
                ->with('success', 'Employee added successfully, but email could not be sent.');
        }

        return redirect()
            ->route('dashboard.admin.showemp')
            ->with('success', 'Employee added successfully.');
    }

    public function changeUserStatus($id)
    {
        $user = User::findOrFail($id);

        $user->status = $user->status === 'active' ? 'inactive' : 'active';
        $user->save();

        if (function_exists('logActivity')) {
            logActivity('Updated User', 'Admin', 'Updated employee status: ' . $user->name);
        }

        return back()->with('success', 'Employee status updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', 'Employee deleted successfully.');
    }
}