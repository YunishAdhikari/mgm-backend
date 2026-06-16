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
use App\Services\FirebaseNotificationService;

class MaintenanceApiController extends Controller
{
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

        $emailSentCount = 0;
        $pushSentCount = 0;

        try {
            $recipients = $this->maintenanceRecipients($job);

            foreach ($recipients as $user) {
                if (!empty($user->email)) {
                    Mail::to($user->email)->send(new MaintenanceAssignedMail($job));
                    $emailSentCount++;
                }
            }

            $pushSentCount = $this->sendPushToUsers(
                $recipients,
                'New Maintenance Job',
                ($job->room_number ? 'Room ' . $job->room_number . ' - ' : '') . $job->title,
                [
                    'type' => 'maintenance',
                    'action' => 'created',
                    'job_id' => (string) $job->id,
                    'priority' => (string) $job->priority,
                    'status' => (string) $job->status,
                ]
            );
        } catch (\Throwable $e) {
            Log::error('Maintenance create notification failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Maintenance task added successfully.',
            'emails_sent' => $emailSentCount,
            'push_sent' => $pushSentCount,
            'job' => $job->load(['department', 'reporter', 'assignedUser']),
        ], 201);
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

        $job = MaintenanceJob::with(['department', 'reporter', 'assignedUser'])->findOrFail($id);

        $oldStatus = $job->status;

        $job->status = $request->status;

        if ($request->status === 'completed') {
            $job->completed_date = now()->toDateString();
        } else {
            $job->completed_date = null;
        }

        $job->save();

        $emailSentCount = 0;
        $pushSentCount = 0;

        try {
            $recipients = $this->maintenanceRecipients($job);

            if ($job->status === 'completed') {
                $emails = User::where('department_id', $job->department_id)
                    ->whereNotNull('email')
                    ->pluck('email');

                foreach ($emails as $email) {
                    Mail::to($email)->send(new MaintenanceCompletedMail($job));
                    $emailSentCount++;
                }

                $pushSentCount = $this->sendPushToUsers(
                    $recipients,
                    'Maintenance Completed',
                    ($job->room_number ? 'Room ' . $job->room_number . ' - ' : '') . $job->title . ' has been completed.',
                    [
                        'type' => 'maintenance',
                        'action' => 'completed',
                        'job_id' => (string) $job->id,
                        'old_status' => (string) $oldStatus,
                        'status' => (string) $job->status,
                    ]
                );
            } else {
                $pushSentCount = $this->sendPushToUsers(
                    $recipients,
                    'Maintenance Status Updated',
                    ($job->room_number ? 'Room ' . $job->room_number . ' - ' : '') . $job->title . ' is now ' . str_replace('_', ' ', $job->status),
                    [
                        'type' => 'maintenance',
                        'action' => 'status_updated',
                        'job_id' => (string) $job->id,
                        'old_status' => (string) $oldStatus,
                        'status' => (string) $job->status,
                    ]
                );
            }
        } catch (\Throwable $e) {
            Log::error('Maintenance status notification failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
            'emails_sent' => $emailSentCount,
            'push_sent' => $pushSentCount,
            'job' => $job->fresh(['department', 'reporter', 'assignedUser']),
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

        $job = MaintenanceJob::with(['department', 'reporter', 'assignedUser'])->findOrFail($id);

        $job->note = $request->note;
        $job->save();

        $pushSentCount = 0;

        try {
            $recipients = $this->maintenanceRecipients($job);

            $pushSentCount = $this->sendPushToUsers(
                $recipients,
                'Maintenance Note Updated',
                ($job->room_number ? 'Room ' . $job->room_number . ' - ' : '') . $job->title,
                [
                    'type' => 'maintenance',
                    'action' => 'note_updated',
                    'job_id' => (string) $job->id,
                    'status' => (string) $job->status,
                ]
            );
        } catch (\Throwable $e) {
            Log::error('Maintenance note notification failed: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Note updated successfully.',
            'push_sent' => $pushSentCount,
            'job' => $job->fresh(['department', 'reporter', 'assignedUser']),
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

    private function maintenanceRecipients(MaintenanceJob $job)
    {
        $departmentNames = [
            'Maintenance',
            'maintenance',
            'Manager',
            'manager',
            'Reception',
            'reception',
        ];

        $departmentIds = Department::whereIn('name', $departmentNames)
            ->pluck('id')
            ->toArray();

        $departmentIds[] = $job->department_id;
        $departmentIds = array_unique($departmentIds);

        return User::where(function ($query) use ($departmentIds, $job) {
                $query->whereIn('department_id', $departmentIds)
                    ->orWhere('id', $job->reported_by)
                    ->orWhere('id', $job->assigned_to)
                    ->orWhereHas('role', function ($roleQuery) {
                        $roleQuery->whereIn('name', [
                            'Admin',
                            'admin',
                            'Manager',
                            'manager',
                            'Department Manager',
                            'department manager',
                            'Duty Manager',
                            'duty manager',
                        ]);
                    });
            })
            ->get()
            ->unique('id')
            ->values();
    }

    private function sendPushToUsers($users, string $title, string $body, array $data = []): int
    {
        $firebase = new FirebaseNotificationService();
        $sent = 0;

        foreach ($users as $user) {
            if (empty($user->fcm_token)) {
                continue;
            }

            try {
                $firebase->sendToToken(
                    $user->fcm_token,
                    $title,
                    $body,
                    $data
                );

                $sent++;
            } catch (\Throwable $e) {
                Log::error('Firebase push failed for user ' . $user->id . ': ' . $e->getMessage());
            }
        }

        return $sent;
    }
}