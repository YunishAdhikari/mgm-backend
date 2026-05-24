<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\MaintenanceController;
use App\Http\Controllers\Admin\NewsController;
use Illuminate\Http\Request;

use App\Models\Role;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmployeeCredentialsMail;
use App\Models\MaintenanceJob;

class AdminDashboard extends Controller
{

public function dashboard()
{
    $totalEmployees = User::count();
    $activeEmployees = User::where('status', 'active')->count();
    $openMaintenance = MaintenanceJob::where('status', '!=', 'completed')->count();
    $activeNews = MaintenanceJob::where('status', 'active')->count();
    $pendingJobs = MaintenanceJob::where('status', 'pending')->count();
    $inProgressJobs = MaintenanceJob::where('status', 'in_progress')->count();
    $completedJobs = MaintenanceJob::where('status', 'completed')->count();
    $cancelledJobs = MaintenanceJob::where('status', 'cancelled')->count();
    $departmentData = Department::withCount('users')->get();
    $departmentNames = $departmentData->pluck('name');
    $departmentCounts = $departmentData->pluck('users_count');

    return view('dashboard.admin.index', compact(
        'totalEmployees',
        'activeEmployees',
        'openMaintenance',
        'activeNews',
        'pendingJobs',
        'inProgressJobs',
        'completedJobs',
        'cancelledJobs',
        'departmentNames',
        'departmentCounts'
    ));
}
public function index(Request $request)
{
    $search = $request->search;

    $users = User::with(['role', 'department'])
        ->when($search, function ($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhereHas('role', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('department', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
        })
        ->latest()
        ->paginate(10);

    return view('dashboard.admin.showemployee', compact('users', 'search'));
}
//     public function index()
// {
//     $users = User::with(['role', 'department'])->get();
//     return view('dashboard.admin.showemployee',compact('users'));
// }

public function addemployeepage(){
    return view('dashboard.admin.addemp');
}

public function create()
    {
        $roles = Role::all();
        $departments = Department::all();
            return view('dashboard.admin.addemp', compact('roles', 'departments'));
        // return view('admin.users.create', compact('roles', 'departments'));
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'phone' => 'nullable|string',
        'password' => 'required|min:6',
        'role_id' => 'required|exists:roles,id',
        'department_id' => 'nullable|exists:departments,id',
    ]);

    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('users', 'public');
    } else {
        $imagePath = null;
    }

    $plainPassword = $request->password;

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone' => $request->phone,
        'password' => Hash::make($plainPassword),
        'role_id' => $request->role_id,
        'image' => $imagePath,
        'department_id' => $request->department_id,
    ]);

    Mail::to($user->email)
        ->send(new EmployeeCredentialsMail($user, $plainPassword));

    return redirect()
        ->route('dashboard')
        ->with('success', 'Employee added successfully.');
}
    public function changeUserStatus($id)
{
    $user = User::findOrFail($id);

    $user->status = $user->status === 'active' ? 'inactive' : 'active';
    $user->save();

    return back()->with('success', 'Employee status updated successfully.');
}

public function destroy($id)
{
    $user = User::findOrFail($id);
    $user->delete();

    return back()->with('success', 'Employee deleted successfully.');
}
}
