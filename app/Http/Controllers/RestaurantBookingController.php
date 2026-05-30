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

    $isOverbooking = $data['pax'] > $availablePax;

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
}