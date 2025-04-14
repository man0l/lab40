<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Http\Resources\BookingCollection;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
    public function index(Request $request): BookingCollection
    {
        $filters = [
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'pin' => $request->input('pin'),
        ];
        
        $bookings = $this->bookingService->getBookings($filters);
        
        return new BookingCollection($bookings);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookingRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $bookingData = [
            'appointment_time' => $validated['appointment_time'],
            'notification_channel_id' => $validated['notification_channel_id'],
            'description' => $validated['description'] ?? null,
        ];
        
        $customerData = [
            'firstname' => $validated['customer']['firstname'],
            'lastname' => $validated['customer']['lastname'],
            'pin' => $validated['customer']['pin'],
        ];

        $booking = $this->bookingService->createBooking($bookingData, $customerData);

        return (new BookingResource($booking))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Booking $booking): BookingResource
    {
        return new BookingResource($booking);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookingRequest $request, Booking $booking): BookingResource
    {
        $validated = $request->validated();
        
        $bookingData = [
            'appointment_time' => $validated['appointment_time'] ?? $booking->appointment_time,
            'notification_channel_id' => $validated['notification_channel_id'] ?? $booking->notification_channel_id,
            'description' => $validated['description'] ?? $booking->description,
        ];
        
        if ($request->input('customer_option') === 'existing') {
            $booking = $this->bookingService->updateBooking(
                $booking, 
                $bookingData, 
                null, 
                $validated['customer_id']
            );
        } elseif ($request->input('customer_option') === 'new') {
            $customerData = [
                'firstname' => $validated['customer']['firstname'],
                'lastname' => $validated['customer']['lastname'],
                'pin' => $validated['customer']['pin'],
            ];
            
            $booking = $this->bookingService->updateBooking(
                $booking, 
                $bookingData, 
                $customerData
            );
        } else {
            $booking = $this->bookingService->updateBooking(
                $booking, 
                $bookingData
            );
        }

        return new BookingResource($booking);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Booking $booking): JsonResponse
    {
        $this->bookingService->deleteBooking($booking);
        
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
