<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Booking;
use App\Models\NotificationChannel;
use App\Models\Customer;
class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = Booking::with(['customer', 'notificationChannel'])
            ->latest()
            ->paginate(10);
            
        return view('booking/index', [
            'bookings' => $bookings
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $notificationChannels = NotificationChannel::all();

        return view('booking/create', [
            'notificationChannels' => $notificationChannels
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookingRequest $request)
    {
        $validated = $request->validated();

        $pin = $request->input('customer.pin');
        $customer = Customer::where('pin', $pin)->first();

        if (!$customer) {
            $customer = Customer::create($validated['customer']);
        }

        $booking = new Booking($validated);
        $booking->customer_id = $customer->id;
        $booking->save();

        return redirect()->route('booking.show', $booking);
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        return view('booking/show', [
            'booking' => $booking
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        $notificationChannels = NotificationChannel::all();

        return view('booking/edit', [
            'booking' => $booking,
            'notificationChannels' => $notificationChannels
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        $validated = $request->validated();
        $booking->update($validated);

        return redirect()->route('booking.show', $booking);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        $booking->delete();
        
        return redirect()->route('booking.index')->with('success', 'Booking deleted successfully');
    }
}
