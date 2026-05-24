<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceJob;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\MaintenanceAssignedMail;
use App\Mail\MaintenanceCompletedMail;

class MaintenanceController extends Controller
{
    public function index()
    {
        $jobs = MaintenanceJob::with(['reporter', 'assignedUser', 'department'])
            ->latest()
            ->get();

        return view('dashboard.admin.maintenance.index', compact('jobs'));
    }

    public function create()
    {
        $departments = Department::all();
        $users = User::all();

        return view('dashboard.admin.maintenance.create', compact('departments', 'users'));
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //     'department_id' => 'required|exists:departments,id',
    //     'assigned_to' => 'nullable|exists:users,id',

    //     'title' => 'required|string|max:255',
    //     'description' => 'required|string',

    //     'location' => 'nullable|string|max:255',
    //     'room_number' => 'nullable|string|max:50',

    //     'priority' => 'required|in:low,medium,high,urgent',

    //     'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    // ]);

    // $imageName = null;

    // if ($request->hasFile('image')) {

    //     $imageName = time() . '.' . $request->image->extension();

    //     $request->image->move(
    //         public_path('uploads/maintenance'),
    //         $imageName
    //     );
    // }

    // MaintenanceJob::create([

    //     'reported_by' => Auth::id(),

    //     'department_id' => $request->department_id,

    //     'assigned_to' => $request->assigned_to,

    //     'title' => $request->title,

    //     'description' => $request->description,

    //     'location' => $request->location,

    //     'room_number' => $request->room_number,

    //     'image' => $imageName,

    //     'priority' => $request->priority,

    //     'status' => 'pending',

    //     'reported_date' => now()->toDateString(),

    // ]);
    
    

    // return redirect()
    //     ->route('admin.maintenance.index')
    //     ->with('success', 'Maintenance job created successfully.');
    // }
public function store(Request $request)
{
    $request->validate([
        'department_id' => 'required|exists:departments,id',
        'assigned_to' => 'nullable|exists:users,id',
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'location' => 'nullable|string|max:255',
        'room_number' => 'nullable|string|max:50',
        'priority' => 'required|in:low,medium,high,urgent',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $imageName = null;

    if ($request->hasFile('image')) {
        $imageName = time() . '.' . $request->image->extension();

        $request->image->move(
            public_path('uploads/maintenance'),
            $imageName
        );
    }

   $job = MaintenanceJob::create([
    'reported_by' => Auth::id(),
    'department_id' => $request->department_id,
    'assigned_to' => $request->assigned_to,
    'title' => $request->title,
    'description' => $request->description,
    'location' => $request->location,
    'room_number' => $request->room_number,
    'image' => $imageName,
    'priority' => $request->priority,
    'status' => 'pending',
    'reported_date' => now()->toDateString(),
]);

// $recipients = User::where('role', 'manager')
//     ->orWhereHas('department', function ($query) {
//         $query->whereIn('name', ['Maintenance', 'Reception']);
//     })
//     ->whereNotNull('email')
//     ->pluck('email')
//     ->unique()
//     ->toArray();

// if (!empty($recipients)) {

//     Mail::to($recipients)
//         ->send(new MaintenanceAssignedMail($job));

// }
$recipients = User::where(function ($query) {
        $query->whereHas('role', function ($roleQuery) {
            $roleQuery->where('name', 'manager');
        })
        ->orWhereHas('department', function ($departmentQuery) {
            $departmentQuery->whereIn('name', ['Maintenance', 'Reception']);
        });
    })
    ->whereNotNull('email')
    ->pluck('email')
    ->unique()
    ->toArray();

if (!empty($recipients)) {
    Mail::to($recipients)->send(new MaintenanceAssignedMail($job));
}
return redirect()
    ->route('admin.maintenance.index')
    ->with('success', 'Maintenance job created successfully.');
}
  public function changeStatus($id)
{
    $job = MaintenanceJob::findOrFail($id);

    if ($job->status === 'pending') {
        $job->status = 'in_progress';
    } elseif ($job->status === 'in_progress') {
        $job->status = 'completed';
        $job->completed_date = now()->toDateString();
    } elseif ($job->status === 'completed') {
        $job->status = 'pending';
        $job->completed_date = null;
    }

    $job->save();

    if ($job->status === 'completed') {
        $recipients = User::where(function ($query) {
                $query->whereHas('role', function ($roleQuery) {
                    $roleQuery->where('name', 'manager');
                })
                ->orWhereHas('department', function ($departmentQuery) {
                    $departmentQuery->whereIn('name', ['Maintenance', 'Reception']);
                });
            })
            ->whereNotNull('email')
            ->pluck('email')
            ->unique()
            ->toArray();

        if (!empty($recipients)) {
            Mail::to($recipients)->send(new MaintenanceCompletedMail($job));
        }
    }

    return back()->with('success', 'Maintenance status updated successfully.');
}
}