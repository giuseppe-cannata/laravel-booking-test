<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition()
    {
        return [
            'customer_id' => Customer::factory(), // Collega la factory di Customer
            'booking_date' => $this->faker->dateTime,
            'notes' => $this->faker->sentence,
        ];
    }
}
