<?php

namespace App\Http\Controllers;

use App\Models\RestaurantBooking;
use App\Models\RestaurantBookingSetting;
use App\Models\RestaurantTable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\RestaurantBookingConfirmationMail;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class RestaurantBookingController extends Controller
{
    public function index()
    {
        return view('restaurant.bookings.index');
    }

    public function slots(Request $request, $type)
    {
        if (!in_array($type, ['afternoon_tea', 'dinner'])) {
            abort(404);
        }

        $bookingDate = $request->get('date', today()->toDateString());

        $setting = RestaurantBookingSetting::where('booking_type', $type)
            ->where('is_active', true)
            ->first();

        $slots = [];

        if ($setting) {
            $start = Carbon::parse($bookingDate . ' ' . $setting->opening_time);
            $end = Carbon::parse($bookingDate . ' ' . $setting->closing_time);

            while ($start->copy()->addMinutes($setting->slot_duration_minutes)->lte($end)) {
                $slotStart = $start->copy();
                $slotEnd = $start->copy()->addMinutes($setting->slot_duration_minutes);

                $bookedPax = RestaurantBooking::where('booking_type', $type)
                    ->whereDate('booking_date', $bookingDate)
                    ->whereTime('slot_start_time', $slotStart->format('H:i:s'))
                    ->whereNotIn('status', ['cancelled', 'no_show'])
                    ->sum('pax');

                $availablePax = $setting->max_pax_per_slot - $bookedPax;

                $slots[] = [
                    'start' => $slotStart->format('H:i:s'),
                    'end' => $slotEnd->format('H:i:s'),
                    'label' => $slotStart->format('g:i A') . ' - ' . $slotEnd->format('g:i A'),
                    'booked_pax' => $bookedPax,
                    'max_pax' => $setting->max_pax_per_slot,
                    'available_pax' => $availablePax,
                    'is_full' => $availablePax <= 0,
                ];

                $start->addMinutes($setting->slot_duration_minutes);
            }
        }

        return view('restaurant.bookings.slots', compact(
            'type',
            'bookingDate',
            'setting',
            'slots'
        ));
    }

    public function create(Request $request, $type, $slotStart, $slotEnd)
    {
        if (!in_array($type, ['afternoon_tea', 'dinner'])) {
            abort(404);
        }

        $bookingDate = $request->get('date', today()->toDateString());

        $tables = RestaurantTable::where('is_active', true)
            ->orderBy('position_y')
            ->orderBy('position_x')
            ->get();

        return view('restaurant.bookings.create', compact(
            'type',
            'slotStart',
            'slotEnd',
            'bookingDate',
            'tables'
        ));
    }

    public function store(Request $request)
{
    $data = $request->validate([
        'booking_type' => 'required|in:afternoon_tea,dinner',
        'restaurant_table_id' => 'required|exists:restaurant_tables,id',
        'guest_name' => 'required|string|max:255',
        'guest_phone' => 'nullable|string|max:255',
        'guest_email' => 'nullable|email|max:255',
        'booking_date' => 'required|date',
        'slot_start_time' => 'required',
        'slot_end_time' => 'required',
        'pax' => 'required|integer|min:1',
        'special_request' => 'nullable|string',
        'force_overbooking' => 'nullable|boolean',
        'voucher_code' => 'nullable|string|max:255',
        'voucher_amount' => 'nullable|numeric|min:0',
        'voucher_note' => 'nullable|string',
    ]);

    $setting = RestaurantBookingSetting::where('booking_type', $data['booking_type'])
        ->where('is_active', true)
        ->firstOrFail();

    $bookedPax = RestaurantBooking::where('booking_type', $data['booking_type'])
        ->whereDate('booking_date', $data['booking_date'])
        ->whereTime('slot_start_time', $data['slot_start_time'])
        ->whereNotIn('status', ['cancelled', 'no_show'])
        ->sum('pax');

    $availablePax = $setting->max_pax_per_slot - $bookedPax;
    $table = RestaurantTable::findOrFail($data['restaurant_table_id']);

    $isOverbooking = $data['pax'] > $availablePax;


        if ($data['pax'] > $table->capacity) {
            return back()
                ->withInput()
                ->withErrors([
                    'pax' => "Selected table capacity is only {$table->capacity} guests."
                ]);
        }

    if ($isOverbooking && !$request->boolean('force_overbooking')) {
        return back()
            ->withInput()
            ->with('overbooking_warning', true)
            ->with('available_pax', $availablePax)
            ->with('message', 'This booking exceeds available pax. Tick proceed as overbooking to continue.');
    }

    $booking = RestaurantBooking::create([
        'booking_type' => $data['booking_type'],
        'restaurant_table_id' => $data['restaurant_table_id'],
        'guest_name' => $data['guest_name'],
        'guest_phone' => $data['guest_phone'] ?? null,
        'guest_email' => $data['guest_email'] ?? null,
        'booking_date' => $data['booking_date'],
        'slot_start_time' => $data['slot_start_time'],
        'slot_end_time' => $data['slot_end_time'],
        'pax' => $data['pax'],
        'is_overbooking' => $isOverbooking,
        'status' => 'confirmed',
        'special_request' => $data['special_request'] ?? null,
        'voucher_code' => $data['voucher_code'] ?? null,
        'voucher_amount' => $data['voucher_amount'] ?? null,
        'voucher_note' => $data['voucher_note'] ?? null,
        'created_by' => auth()->id(),
    ]);

    if (!empty($booking->guest_email)) {
        try {
            Mail::to($booking->guest_email)
                ->send(new RestaurantBookingConfirmationMail($booking));
        } catch (\Exception $e) {
            Log::error('Restaurant booking confirmation email failed: ' . $e->getMessage());
        }
    }

    return redirect()
        ->route('reception.restaurant.bookings.index')
        ->with('success', 'Restaurant booking created successfully.');
}

// ---------------Afternoon and dinnner booking report-------------------
public function report(Request $request)
{
    $query = RestaurantBooking::with(['table', 'creator'])
        ->orderBy('booking_date', 'desc')
        ->orderBy('slot_start_time', 'asc');

    if ($request->filled('booking_type')) {
        $query->where('booking_type', $request->booking_type);
    }

    if ($request->filled('date_from')) {
        $query->whereDate('booking_date', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
        $query->whereDate('booking_date', '<=', $request->date_to);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $bookings = $query->get();

    $totalBookings = $bookings->count();
    $totalPax = $bookings->sum('pax');
    $overBookings = $bookings->where('is_overbooking', true)->count();

    return view('restaurant.bookings.report', compact(
        'bookings',
        'totalBookings',
        'totalPax',
        'overBookings'
    ));
}

//---------------report pdf --------------
public function reportPdf(Request $request)
{
    $query = RestaurantBooking::with(['table', 'creator'])
        ->orderBy('booking_date', 'desc')
        ->orderBy('slot_start_time', 'asc');

    if ($request->filled('booking_type')) {
        $query->where('booking_type', $request->booking_type);
    }

    if ($request->filled('date_from')) {
        $query->whereDate('booking_date', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
        $query->whereDate('booking_date', '<=', $request->date_to);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $bookings = $query->get();

    $pdf = Pdf::loadView('restaurant.bookings.report-pdf', [
        'bookings' => $bookings,
        'totalBookings' => $bookings->count(),
        'totalPax' => $bookings->sum('pax'),
        'overBookings' => $bookings->where('is_overbooking', true)->count(),
    ])->setPaper('a4', 'landscape');

    return $pdf->download('restaurant-booking-report.pdf');
}

//--------------reception booking list-----------
public function list(Request $request)
{
    $query = RestaurantBooking::with(['table', 'creator'])
        ->orderBy('booking_date', 'desc')
        ->orderBy('slot_start_time', 'asc');


    if ($request->filled('date')) {
        $query->whereDate('booking_date', $request->date);
    } 
    if ($request->filled('search')) {

    $query->where(function ($q) use ($request) {

        $q->where('guest_name', 'like', '%' . $request->search . '%')
          ->orWhere('guest_phone', 'like', '%' . $request->search . '%')
          ->orWhere('guest_email', 'like', '%' . $request->search . '%');

    });
}
    else {
        $query->whereDate('booking_date', today());
    }

    if ($request->filled('booking_type')) {
        $query->where('booking_type', $request->booking_type);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $bookings = $query->get();

    return view('restaurant.bookings.list', compact('bookings'));
}

//------------update//edit//delete------------------
public function show(RestaurantBooking $booking)
{
    $booking->load(['table', 'creator']);

    return view('restaurant.bookings.show', compact('booking'));
}

public function edit(RestaurantBooking $booking)
{
    $bookedTableIds = RestaurantBooking::whereDate('booking_date', $booking->booking_date)
    ->whereTime('slot_start_time', $booking->slot_start_time)
    ->whereNotIn('status', ['cancelled', 'no_show'])
    ->where('id', '!=', $booking->id)
    ->pluck('restaurant_table_id')
    ->toArray();

$tables = RestaurantTable::where('is_active', true)
    ->orderBy('position_y')
    ->orderBy('position_x')
    ->get();

return view('restaurant.bookings.edit', compact(
    'booking',
    'tables',
    'bookedTableIds'
));
}

public function update(Request $request, RestaurantBooking $booking)
{
    $data = $request->validate([
        'restaurant_table_id' => 'required|exists:restaurant_tables,id',
        'booking_date' => 'required|date',
        'slot_start_time' => 'required',
        'slot_end_time' => 'required',
        'guest_name' => 'required|string|max:255',
        'guest_phone' => 'nullable|string|max:255',
        'guest_email' => 'nullable|email|max:255',
        'pax' => 'required|integer|min:1',
        'status' => 'required|in:confirmed,seated,completed,cancelled,no_show',
        'special_request' => 'nullable|string',
        'voucher_code' => 'nullable|string|max:255',
        'voucher_amount' => 'nullable|numeric|min:0',
        'voucher_note' => 'nullable|string',
        'force_overbooking' => 'nullable|boolean',
    ]);

    $setting = RestaurantBookingSetting::where('booking_type', $booking->booking_type)
        ->where('is_active', true)
        ->firstOrFail();

    $bookedPax = RestaurantBooking::where('booking_type', $booking->booking_type)
        ->whereDate('booking_date', $data['booking_date'])
        ->whereTime('slot_start_time', $data['slot_start_time'])
        ->whereNotIn('status', ['cancelled', 'no_show'])
        ->where('id', '!=', $booking->id)
        ->sum('pax');

    $availablePax = $setting->max_pax_per_slot - $bookedPax;
    $table = RestaurantTable::findOrFail($data['restaurant_table_id']);
    $isOverbooking = $data['pax'] > $availablePax;


        if ($data['pax'] > $table->capacity) {
            return back()
                ->withInput()
                ->withErrors([
                    'pax' => "Selected table capacity is only {$table->capacity} guests."
                ]);
        }

    if ($isOverbooking && !$request->boolean('force_overbooking')) {
        return back()
            ->withInput()
            ->with('overbooking_warning', true)
            ->with('available_pax', $availablePax)
            ->with('message', 'This update exceeds available pax for the selected slot. Tick proceed as overbooking to continue.');
    }

    $booking->update([
        'restaurant_table_id' => $data['restaurant_table_id'],
        'booking_date' => $data['booking_date'],
        'slot_start_time' => $data['slot_start_time'],
        'slot_end_time' => $data['slot_end_time'],
        'guest_name' => $data['guest_name'],
        'guest_phone' => $data['guest_phone'] ?? null,
        'guest_email' => $data['guest_email'] ?? null,
        'pax' => $data['pax'],
        'status' => $data['status'],
        'special_request' => $data['special_request'] ?? null,
        'voucher_code' => $data['voucher_code'] ?? null,
        'voucher_amount' => $data['voucher_amount'] ?? null,
        'voucher_note' => $data['voucher_note'] ?? null,
        'is_overbooking' => $isOverbooking,
    ]);

    return redirect()
        ->route('reception.restaurant.bookings.show', $booking)
        ->with('success', 'Booking updated successfully.');
}

public function cancel(RestaurantBooking $booking)
{
    $booking->update([
        'status' => 'cancelled',
    ]);

    return back()->with('success', 'Booking cancelled successfully.');
}
}