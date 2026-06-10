<?php

namespace App\Http\Controllers\Reception;

use App\Http\Controllers\Controller;
use App\Models\GroupBuffetBooking;
use App\Models\RestaurantTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GroupBuffetBookingController extends Controller
{
public function index(Request $request)
{
    $query = GroupBuffetBooking::with('tables');

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    } else {
        $query->where('status', '!=', 'cancelled');
    }

    if ($request->filled('meal_type')) {
        $query->where('meal_type', $request->meal_type);
    }

    if ($request->filled('date')) {
        $query->whereDate('buffet_date', $request->date);
    }

    $bookings = $query->latest()->paginate(15);

    return view(
        'dashboard.reception.group-buffets.index',
        compact('bookings')
    );
}

    public function create()
    {
        return view('dashboard.reception.group-buffets.create');
    }


    public function dailyReport(Request $request)
{
    $date = $request->date ?? today()->toDateString();

    $query = GroupBuffetBooking::with('tables')
        ->whereDate('buffet_date', $date);

    if ($request->filled('meal_type')) {
        $query->where('meal_type', $request->meal_type);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    } else {
        $query->whereIn('status', ['confirmed', 'served']);
    }

    $bookings = $query
        ->orderBy('buffet_time')
        ->get();

    return view('dashboard.reception.group-buffets.daily-report', compact(
        'bookings',
        'date'
    ));
}

    private function restaurantCapacity()
    {
        return RestaurantTable::where('is_active', true)
            ->where('status', 'available')
            ->sum('capacity');
    }

    private function checkQueueCapacity($date, $time, $pax, $excludeBookingId = null)
    {
        $queueCap = 50;
        $queueWindowMinutes = 30;

        $slotTime = Carbon::parse($time);

        $windowStart = $slotTime->copy()->subMinutes($queueWindowMinutes - 1)->format('H:i:s');
        $windowEnd = $slotTime->copy()->format('H:i:s');

        $query = GroupBuffetBooking::whereDate('buffet_date', $date)
            ->whereIn('status', ['confirmed', 'served'])
            ->whereBetween('buffet_time', [$windowStart, $windowEnd]);

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        $existingPax = $query->sum('pax');

        return ($existingPax + $pax) <= $queueCap;
    }

    private function checkRestaurantCapacity($date, $time, $pax, $excludeBookingId = null)
    {
        $slotDurationMinutes = 45;
        $restaurantCapacity = $this->restaurantCapacity();

        $newStart = Carbon::parse($time);
        $newEnd = $newStart->copy()->addMinutes($slotDurationMinutes);

        $query = GroupBuffetBooking::whereDate('buffet_date', $date)
            ->whereIn('status', ['confirmed', 'served']);

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        $existingBookings = $query->get();

        $overlappingPax = 0;

        foreach ($existingBookings as $booking) {
            $existingStart = Carbon::parse($booking->buffet_time);
            $existingEnd = $existingStart->copy()->addMinutes($slotDurationMinutes);

            if ($newStart < $existingEnd && $newEnd > $existingStart) {
                $overlappingPax += $booking->pax;
            }
        }

        return ($overlappingPax + $pax) <= $restaurantCapacity;
    }

    private function allocateTables($date, $time, $pax, $excludeBookingId = null)
    {
        $slotDurationMinutes = 45;

        $newStart = Carbon::parse($time);
        $newEnd = $newStart->copy()->addMinutes($slotDurationMinutes);

        $bookedTableQuery = DB::table('group_buffet_booking_tables')
            ->join('group_buffet_bookings', 'group_buffet_booking_tables.group_buffet_booking_id', '=', 'group_buffet_bookings.id')
            ->whereDate('group_buffet_bookings.buffet_date', $date)
            ->whereIn('group_buffet_bookings.status', ['confirmed', 'served']);

        if ($excludeBookingId) {
            $bookedTableQuery->where('group_buffet_bookings.id', '!=', $excludeBookingId);
        }

        $bookedRows = $bookedTableQuery
            ->select(
                'group_buffet_booking_tables.restaurant_table_id',
                'group_buffet_bookings.buffet_time'
            )
            ->get();

        $bookedTableIds = [];

        foreach ($bookedRows as $row) {
            $existingStart = Carbon::parse($row->buffet_time);
            $existingEnd = $existingStart->copy()->addMinutes($slotDurationMinutes);

            if ($newStart < $existingEnd && $newEnd > $existingStart) {
                $bookedTableIds[] = $row->restaurant_table_id;
            }
        }

        $availableTables = RestaurantTable::where('is_active', true)
            ->where('status', 'available')
            ->whereNotIn('id', $bookedTableIds)
            ->orderByDesc('capacity')
            ->get();

        $selectedTables = collect();
        $totalCapacity = 0;

        foreach ($availableTables as $table) {
            if ($totalCapacity >= $pax) {
                break;
            }

            $selectedTables->push($table);
            $totalCapacity += $table->capacity;
        }

        if ($totalCapacity < $pax) {
            return null;
        }

        return $selectedTables;
    }

    private function suggestAvailableSlots($date, $requestedTime, $pax)
    {
        $requested = Carbon::parse($requestedTime);

        $slotsToCheck = [
            $requested->copy()->addMinutes(15),
            $requested->copy()->addMinutes(30),
            $requested->copy()->addMinutes(45),
            $requested->copy()->addMinutes(60),
            $requested->copy()->addMinutes(75),
            $requested->copy()->addMinutes(90),
            $requested->copy()->addMinutes(105),
            $requested->copy()->addMinutes(120),
        ];

        $suggestions = [];

        foreach ($slotsToCheck as $slot) {
            $time = $slot->format('H:i:s');

            if (!$this->checkQueueCapacity($date, $time, $pax)) {
                continue;
            }

            if (!$this->checkRestaurantCapacity($date, $time, $pax)) {
                continue;
            }

            $tables = $this->allocateTables($date, $time, $pax);

            if ($tables) {
                $suggestions[] = [
                    'time' => $slot->format('H:i'),
                    'display_time' => $slot->format('h:i A'),
                    'tables' => $tables->map(function ($table) {
                        return $table->table_name . ' (' . $table->capacity . ')';
                    })->toArray(),
                    'total_capacity' => $tables->sum('capacity'),
                ];
            }
        }

        return collect($suggestions)->take(3);
    }

    public function store(Request $request)
    {
        $request->validate([
            'group_name' => 'required|string|max:255',
            'agent_name' => 'nullable|string|max:255',
            'buffet_date' => 'required|date',
            'buffet_time' => 'required',
            'pax' => 'required|integer|min:1',
            'meal_type' => 'required|in:breakfast,lunch,dinner,afternoon_tea,private_event',
            'price_per_person' => 'nullable|numeric|min:0',
            'payment_status' => 'required|in:pending,paid,city_ledger,complimentary',
            'notes' => 'nullable|string',
        ]);

        if (!$this->checkQueueCapacity($request->buffet_date, $request->buffet_time, $request->pax)) {
            $suggestedSlots = $this->suggestAvailableSlots(
                $request->buffet_date,
                $request->buffet_time,
                $request->pax
            );

            return back()
                ->withInput()
                ->with('error', 'High occupancy: too many guests are arriving around this time. Please choose a suggested slot.')
                ->with('suggestedSlots', $suggestedSlots);
        }

        if (!$this->checkRestaurantCapacity($request->buffet_date, $request->buffet_time, $request->pax)) {
            $suggestedSlots = $this->suggestAvailableSlots(
                $request->buffet_date,
                $request->buffet_time,
                $request->pax
            );

            return back()
                ->withInput()
                ->with('error', 'Restaurant capacity is full during this time. Please choose a later suggested slot.')
                ->with('suggestedSlots', $suggestedSlots);
        }

        $tables = $this->allocateTables(
            $request->buffet_date,
            $request->buffet_time,
            $request->pax
        );

        if (!$tables) {
            $suggestedSlots = $this->suggestAvailableSlots(
                $request->buffet_date,
                $request->buffet_time,
                $request->pax
            );

            return back()
                ->withInput()
                ->with('error', 'Not enough available tables for this group at the selected time.')
                ->with('suggestedSlots', $suggestedSlots);
        }

        $totalAmount = $request->price_per_person
            ? $request->pax * $request->price_per_person
            : null;

        DB::transaction(function () use ($request, $tables, $totalAmount) {
            $booking = GroupBuffetBooking::create([
                'group_name' => $request->group_name,
                'agent_name' => $request->agent_name,
                'buffet_date' => $request->buffet_date,
                'buffet_time' => $request->buffet_time,
                'pax' => $request->pax,
                'meal_type' => $request->meal_type,
                'price_per_person' => $request->price_per_person,
                'total_amount' => $totalAmount,
                'payment_status' => $request->payment_status,
                'status' => 'confirmed',
                'notes' => $request->notes,
                'created_by' => auth()->id(),
            ]);

            $booking->tables()->attach($tables->pluck('id')->toArray());
        });

        return redirect()
            ->route('reception.group-buffets.index')
            ->with('success', 'Group buffet booking created successfully with auto table allocation.');
    }

    public function update(Request $request, GroupBuffetBooking $groupBuffet)
    {
        $request->validate([
            'group_name' => 'required|string|max:255',
            'agent_name' => 'nullable|string|max:255',
            'buffet_date' => 'required|date',
            'buffet_time' => 'required',
            'pax' => 'required|integer|min:1',
            'meal_type' => 'required|in:breakfast,lunch,dinner,afternoon_tea,private_event',
            'price_per_person' => 'nullable|numeric|min:0',
            'payment_status' => 'required|in:pending,paid,city_ledger,complimentary',
            'status' => 'required|in:confirmed,served,completed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $totalAmount = $request->price_per_person
            ? $request->pax * $request->price_per_person
            : null;

        DB::transaction(function () use ($request, $groupBuffet, $totalAmount) {
            $groupBuffet->update([
                'group_name' => $request->group_name,
                'agent_name' => $request->agent_name,
                'buffet_date' => $request->buffet_date,
                'buffet_time' => $request->buffet_time,
                'pax' => $request->pax,
                'meal_type' => $request->meal_type,
                'price_per_person' => $request->price_per_person,
                'total_amount' => $totalAmount,
                'payment_status' => $request->payment_status,
                'status' => $request->status,
                'notes' => $request->notes,
            ]);

            if ($request->status === 'cancelled' || $request->status === 'completed') {
                $groupBuffet->tables()->detach();
            }
        });

        return redirect()
            ->route('reception.group-buffets.index')
            ->with('success', 'Group buffet booking updated successfully.');
    }
}