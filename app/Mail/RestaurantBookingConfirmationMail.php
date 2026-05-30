<?php

namespace App\Mail;

use App\Models\RestaurantBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RestaurantBookingConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public RestaurantBooking $booking;

    public function __construct(RestaurantBooking $booking)
    {
        $this->booking = $booking;
    }

    public function build()
    {
        return $this->subject('Restaurant Booking Confirmation')
            ->view('emails.restaurant-booking-confirmation');
    }
}