<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'appointment_time' => $this->appointment_time,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'customer' => [
                'id' => $this->customer->id,
                'firstname' => $this->customer->firstname,
                'lastname' => $this->customer->lastname,
                'pin' => $this->customer->pin
            ],
            'notification_channel' => [
                'id' => $this->notificationChannel->id,
                'name' => $this->notificationChannel->name,
            ],
        ];
    }
} 