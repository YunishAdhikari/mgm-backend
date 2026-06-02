<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\MaintenanceAssignedMail;
use App\Mail\MaintenanceCompletedMail;
use App\Models\Department;
use App\Models\MaintenanceJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MaintenanceApiController extends Controller
{
    // public function index(Request $request)
    // {
    //     $user = $request->user();

    //     $query = MaintenanceJob::with(['department', 'reporter', 'assignedUser'])
    //         ->where('status', '!=', 'completed')
    //         ->latest();

    //     return response()->json([
    //         'success' => true,
    //         'jobs' => $query->get(),
    //     ]);
    // }
    public function index(Request $request)
{
    $query = MaintenanceJob::with([
        'department',
        'reporter',
        'assignedUser'
    ]);

    if ($request->status) {
        $query->where('status', $request->status);
    }

    if ($request->priority) {
        $query->where('priority', $request->priority);
    }

    if ($request->assigned === 'me') {
        $query->where('assigned_to', $request->user()->id);
    }

    $jobs = $query->latest()->get();

    return response()->json([
        'success' => true,
        'jobs' => $jobs,
    ]);
}

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
            $request->image->move(public_path('uploads/maintenance'), $imageName);
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


try {
    $departmentNames = [
        'Maintenance',
        'manager',
        'Reception',
    ];

    $departmentIds = Department::whereIn('name', $departmentNames)
        ->pluck('id')
        ->toArray();

    // Also include the department selected in the task
    $departmentIds[] = $job->department_id;

    $departmentIds = array_unique($departmentIds);

    $recipients = User::whereNotNull('email')
        ->where(function ($query) use ($departmentIds) {
            $query->whereIn('department_id', $departmentIds)
                ->orWhereHas('role', function ($roleQuery) {
                    $roleQuery->whereIn('name', [
                        'Manager',
                        'manager',
                        'Department Manager',
                        'department manager',
                    ]);
                });
        })
        ->get()
        ->unique('email')
        ->values();

    $emailSentCount = 0;

    foreach ($recipients as $user) {
        Mail::to($user->email)->send(new MaintenanceAssignedMail($job));
        $emailSentCount++;
    }

} catch (\Throwable $e) {
    Log::error('Maintenance email failed: ' . $e->getMessage());
}
return response()->json([
    'success' => true,
    'message' => 'Maintenance task added successfully.',
    'emails_sent' => $emailSentCount ?? 0,
    'job' => $job,
], 201);
    }

    public function show(Request $request, $id)
    {
        $job = MaintenanceJob::with(['department', 'reporter', 'assignedUser'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'job' => $job,
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
        ]);

        $user = $request->user();

        if ($user->department?->name !== 'Maintenance') {
            return response()->json([
                'success' => false,
                'message' => 'Only maintenance department can update task status.',
            ], 403);
        }

        $job = MaintenanceJob::findOrFail($id);

        $job->status = $request->status;

        if ($request->status === 'completed') {
            $job->completed_date = now()->toDateString();
        } else {
            $job->completed_date = null;
        }

        $job->save();
        if ($job->status === 'completed') {

                $emails = User::where('department_id', $job->department_id)
                    ->whereNotNull('email')
                    ->pluck('email');

                foreach ($emails as $email) {
                    Mail::to($email)->send(
                        new MaintenanceCompletedMail($job)
                    );
                }
            }

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
            'job' => $job,
        ]);
    }

    public function updateNote(Request $request, $id)
    {
        $request->validate([
            'note' => 'required|string|max:2000',
        ]);

        $user = $request->user();

        if ($user->department?->name !== 'Maintenance') {
            return response()->json([
                'success' => false,
                'message' => 'Only maintenance department can update task notes.',
            ], 403);
        }

        $job = MaintenanceJob::findOrFail($id);

        $job->note = $request->note;
        $job->save();

        return response()->json([
            'success' => true,
            'message' => 'Note updated successfully.',
            'job' => $job,
        ]);
    }

    public function myJobs(Request $request)
{
    $jobs = MaintenanceJob::with(['department', 'reporter'])
        ->where('assigned_to', $request->user()->id)
        ->where('status', '!=', 'completed')
        ->latest()
        ->get();

    return response()->json([
        'success' => true,
        'jobs' => $jobs,
    ]);
}
}