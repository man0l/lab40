<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Booking;
use App\Services\BookingService;

class BookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filters = [
            'start_date' => request('start_date'),
            'end_date' => request('end_date'),
            'pin' => request('pin'),
        ];
        
        $bookings = $this->bookingService->getBookings($filters);
        
        return view('booking/index', [
            'bookings' => $bookings
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $notificationChannels = $this->bookingService->getNotificationChannels();

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

        $bookingData = [
            'appointment_time' => $validated['appointment_time'],
            'notification_channel_id' => $validated['notification_channel_id'],
            'description' => $validated['description'] ?? null,
        ];

        $booking = $this->bookingService->createBooking($bookingData, $validated['customer']);

        $notificationChannel = $this->bookingService->getNotificationChannel($booking->notification_channel_id);
        $channelName = $notificationChannel ? $notificationChannel->name : 'notification';

        return redirect()->route('booking.show', $booking)
            ->with('success', "You have successfully booked an appointment! The client will be notified via {$channelName}.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking)
    {
        $upcomingAppointments = $this->bookingService->getUpcomingAppointments(
            $booking->customer_id, 
            $booking->id
        );

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
        $notificationChannels = $this->bookingService->getNotificationChannels();
        $customers = $this->bookingService->getCustomers();

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
        
        $bookingData = [
            'appointment_time' => $validated['appointment_time'],
            'notification_channel_id' => $validated['notification_channel_id'],
            'description' => $validated['description'] ?? null,
        ];
        
        if ($request->input('customer_option') === 'existing') {
            $booking = $this->bookingService->updateBooking(
                $booking, 
                $bookingData, 
                null, 
                $validated['customer_id']
            );
        } else {
            $booking = $this->bookingService->updateBooking(
                $booking, 
                $bookingData, 
                $validated['customer']
            );
        }

        $notificationChannel = $this->bookingService->getNotificationChannel($booking->notification_channel_id);
        $channelName = $notificationChannel ? $notificationChannel->name : 'notification';

        return redirect()->route('booking.show', $booking)
            ->with('success', "You have successfully updated the appointment! The client will be notified via {$channelName}.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking)
    {
        $this->bookingService->deleteBooking($booking);
        
        return redirect()->route('booking.index')->with('success', 'Booking deleted successfully');
    }
}
