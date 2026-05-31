<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    public function publicForm()
    {
        return view('guest.complaint');
    }

    public function store(Request $request)
    {
        $request->validate([
            'guest_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:30',
            'room_number' => 'nullable|string|max:50',
            'type' => 'required|in:complaint,feedback',
            'category' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|in:low,medium,high,urgent',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imageName = null;

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();

            $request->image->move(
                public_path('uploads/complaints'),
                $imageName
            );
        }

        Complaint::create([
            'guest_name' => $request->guest_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'room_number' => $request->room_number,
            'type' => $request->type,
            'category' => $request->category,
            'title' => $request->title,
            'description' => $request->description,
            'priority' => $request->priority,
            'image' => $imageName,
            'status' => 'pending',
        ]);

        return redirect()
            ->back()
            ->with('success', 'Thank you. Your message has been submitted successfully.');
    }

    public function index()
    {
         $complaints = Complaint::with(['creator', 'handler'])
        ->latest()
        ->get();

        return view('dashboard.admin.complaints.index', compact('complaints'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,resolved,closed',
            'internal_note' => 'nullable|string',
        ]);

        $complaint = Complaint::findOrFail($id);

        $complaint->update([
            'status' => $request->status,
            'internal_note' => $request->internal_note,
            'handled_by' => Auth::id(),
        ]);

        return back()->with('success', 'Complaint status updated successfully.');
    }
}