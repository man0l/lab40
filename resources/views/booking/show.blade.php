<x-layout>
    <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
            <h2 class="mb-6 text-xl font-bold text-gray-900 dark:text-white flex items-center">
                <svg class="w-6 h-6 text-blue-600 dark:text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Booking Details
            </h2>
            
            <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                <div class="grid gap-2">
                    <div class="flex">
                        <span class="font-semibold text-gray-700 dark:text-gray-300 w-40">Booking ID:</span>
                        <span class="text-gray-900 dark:text-white">{{ $booking->id }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-semibold text-gray-700 dark:text-gray-300 w-40">Appointment Time:</span>
                        <span class="text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($booking->appointment_time)->format('F j, Y, g:i a') }}</span>
                    </div>
                </div>
            </div>
            
            <h3 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">Customer Information</h3>
            <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                <div class="grid gap-2">
                    <div class="flex">
                        <span class="font-semibold text-gray-700 dark:text-gray-300 w-40">Name:</span>
                        <span class="text-gray-900 dark:text-white">{{ $booking->customer->first_name }} {{ $booking->customer->last_name }}</span>
                    </div>
                    <div class="flex">
                        <span class="font-semibold text-gray-700 dark:text-gray-300 w-40">PIN:</span>
                        <span class="text-gray-900 dark:text-white">{{ $booking->customer->pin }}</span>
                    </div>
                </div>
            </div>
            
            <h3 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">Notification</h3>
            <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                <div class="flex">
                    <span class="font-semibold text-gray-700 dark:text-gray-300 w-40">Method:</span>
                    <span class="text-gray-900 dark:text-white">{{ $booking->notificationChannel->name }}</span>
                </div>
            </div>
            
            <div class="flex items-center justify-between mt-6">
                <a href="{{ route('booking.index') }}" class="text-sm font-medium text-blue-600 dark:text-blue-500 hover:underline">
                    <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to List
                </a>
                <div class="space-x-2">
                    <a href="{{ route('booking.edit', $booking) }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                    </a>
                    <form method="POST" action="{{ route('booking.destroy', $booking) }}" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this booking?')" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layout>
