<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Booking;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'appointment_time' => [
                'required',
                'date_format:Y-m-d\TH:i',
                'after:now',
                function ($attribute, $value, $fail) {
                    if (Booking::where('appointment_time', $value)->exists()) {
                        $fail('The selected appointment time is already booked. Please choose a different time.');
                    }
                },
            ],
            'description' => 'nullable|string|max:500',
            'customer.firstname' => 'required|string|max:255',
            'customer.lastname' => 'required|string|max:255',
            'customer.pin' => 'required|numeric|digits_between:9,10',
            'notification_channel_id' => 'required|exists:notification_channels,id'
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'customer.firstname' => 'first name',
            'customer.lastname' => 'last name',
            'customer.pin' => 'personal identification number',
            'notification_channel_id' => 'notification method',
            'description' => 'booking description'
        ];
    }
}
