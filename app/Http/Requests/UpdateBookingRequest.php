<?php

namespace App\Http\Requests;

use App\Models\Booking;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBookingRequest extends FormRequest
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
        $rules = [
            'appointment_time' => [
                'required',
                'date_format:Y-m-d\TH:i',
                'after:now',
                function ($attribute, $value, $fail) {
                    $bookingId = $this->route('booking')->id;
                    
                    $exists = Booking::where('appointment_time', $value)
                        ->where('id', '!=', $bookingId)
                        ->exists();
                    
                    if ($exists) {
                        $fail('The selected appointment time is already booked. Please choose a different time.');
                    }
                },
            ],
            'description' => 'nullable|string|max:500',
            'notification_channel_id' => 'required|exists:notification_channels,id',
            'customer_option' => 'required|in:existing,new',
        ];

        if ($this->input('customer_option') === 'existing') {
            $rules['customer_id'] = 'required|exists:customers,id';
        } else {
            $rules['customer.firstname'] = 'required|string|max:255';
            $rules['customer.lastname'] = 'required|string|max:255';
            $rules['customer.pin'] = 'required|string|size:10|unique:customers,pin';
        }

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'appointment_time' => 'appointment time',
            'notification_channel_id' => 'notification method',
            'customer_id' => 'customer',
            'customer.firstname' => 'first name',
            'customer.lastname' => 'last name',
            'customer.pin' => 'personal identification number',
            'description' => 'booking description'
        ];
    }
}
