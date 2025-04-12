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

        $booking = new Booking([
            'appointment_time' => $validated['appointment_time'],
            'notification_channel_id' => $validated['notification_channel_id'],
            'description' => $validated['description'] ?? null,
        ]);
        $booking->customer_id = $customer->id;
        $booking->save();

        $notificationChannel = NotificationChannel::find($booking->notification_channel_id);
        $channelName = $notificationChannel ? $notificationChannel->name : 'notification';

        return redirect()->route('booking.show', $booking)
            ->with('success', "You have successfully booked an appointment! The client will be notified via {$channelName}.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        $upcomingAppointments = Booking::where('customer_id', $booking->customer_id)
            ->where('id', '!=', $booking->id)
            ->where('appointment_time', '>', now())
            ->orderBy('appointment_time', 'asc')
            ->limit(5)
            ->get();

        return view('booking/show', [
            'booking' => $booking,
            'upcomingAppointments' => $upcomingAppointments
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        $notificationChannels = NotificationChannel::all();
        $customers = Customer::all();

        return view('booking/edit', [
            'booking' => $booking,
            'notificationChannels' => $notificationChannels,
            'customers' => $customers
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookingRequest $request, Booking $booking)
    {
        $validated = $request->validated();
        
        if ($request->input('customer_option') === 'existing') {
            $booking->customer_id = $validated['customer_id'];
        } else {
            $customerData = $validated['customer'];        
            
            $customer = Customer::where('pin', $customerData['pin'])->first();
            
            if (!$customer) {
                $customer = Customer::create($customerData);
            }
            
            $booking->customer_id = $customer->id;
        }
        
        $booking->appointment_time = $validated['appointment_time'];
        $booking->notification_channel_id = $validated['notification_channel_id'];
        $booking->description = $validated['description'] ?? null;
        $booking->save();

        $notificationChannel = NotificationChannel::find($booking->notification_channel_id);
        $channelName = $notificationChannel ? $notificationChannel->name : 'notification';

        return redirect()->route('booking.show', $booking)
            ->with('success', "You have successfully updated the appointment! The client will be notified via {$channelName}.");
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
