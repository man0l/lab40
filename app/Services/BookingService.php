<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Customer;
use App\Models\NotificationChannel;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
class BookingService
{
    /**
     * Get filtered and paginated bookings
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function getBookings($filters = [])
    {
        $query = Booking::query();
        
        $query->with('notificationChannel');
        
        if (isset($filters['start_date']) && $filters['start_date']) {
            $query->whereDate('appointment_time', '>=', $filters['start_date']);
        }
        
        if (isset($filters['end_date']) && $filters['end_date']) {
            $query->whereDate('appointment_time', '<=', $filters['end_date']);
        }
        
        if (isset($filters['pin']) && $filters['pin']) {
            $query->whereHas('customer', function($q) use ($filters) {
                $q->where('pin', 'LIKE', '%' . $filters['pin'] . '%');
            });
        }
                
        $query->with('customer');
        
        return $query->latest()->paginate(10)->withQueryString();
    }
    
    /**
     * Create a new booking
     *
     * @param array $bookingData
     * @param array $customerData
     * @return Booking
     */
    public function createBooking(array $bookingData, array $customerData)
    {
        DB::beginTransaction();
        try {
            $customer = $this->findOrCreateCustomer($customerData);
            
        $booking = new Booking([
            'appointment_time' => $bookingData['appointment_time'],
            'notification_channel_id' => $bookingData['notification_channel_id'],
            'description' => $bookingData['description'] ?? null,
        ]);

            $booking->customer_id = $customer->id;
            $booking->save();

            DB::commit();
            return $booking;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    /**
     * Update an existing booking
     *
     * @param Booking $booking
     * @param array $bookingData
     * @param array|null $customerData
     * @param int|null $customerId
     * @return Booking
     */
    public function updateBooking(Booking $booking, array $bookingData, ?array $customerData = null, ?int $customerId = null)
    {
        DB::beginTransaction();
        try {
            if ($customerId) {
                $booking->customer_id = $customerId;
        } elseif ($customerData) {
            $customer = $this->findOrCreateCustomer($customerData);
            $booking->customer_id = $customer->id;
        }
        
            $booking->appointment_time = $bookingData['appointment_time'];
            $booking->notification_channel_id = $bookingData['notification_channel_id'];
            $booking->description = $bookingData['description'] ?? null;
            $booking->save();

            DB::commit();
            return $booking;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    /**
     * Delete a booking
     *
     * @param Booking $booking
     * @return bool|null
     */
    public function deleteBooking(Booking $booking)
    {
        return $booking->delete();
    }
    
    /**
     * Find customer by PIN or create a new one
     *
     * @param array $customerData
     * @return Customer
     */
    public function findOrCreateCustomer(array $customerData)
    {
        $customer = Customer::where('pin', $customerData['pin'])->first();
        
        if (!$customer) {            
            $customer = Customer::create($customerData);
        }
        
        return $customer;
    }
    
    /**
     * Get all notification channels
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getNotificationChannels()
    {
        return NotificationChannel::all();
    }
    
    /**
     * Get all customers
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCustomers()
    {
        return Customer::all();
    }
    
    /**
     * Get upcoming appointments for a customer
     *
     * @param int $customerId
     * @param int|null $excludeBookingId
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUpcomingAppointments($customerId, $excludeBookingId = null, $limit = 5)
    {
        $query = Booking::where('customer_id', $customerId)
            ->where('appointment_time', '>', now())
            ->orderBy('appointment_time', 'asc');
            
        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }
        
        return $query->limit($limit)->get();
    }
    
    /**
     * Get notification channel by ID
     *
     * @param int $channelId
     * @return NotificationChannel|null
     */
    public function getNotificationChannel($channelId)
    {
        return NotificationChannel::find($channelId);
    }
}