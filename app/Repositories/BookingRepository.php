<?php

namespace App\Repositories;

use App\Models\Booking;

class BookingRepository
{
    public function getAllWithCustomer()
    {
        return Booking::with('customer')->get();
    }

    public function findById($id)
    {
        return Booking::find($id);
    }

    public function findByIdWithCustomer($id)
    {
        return Booking::with('customer')->find($id);
    }

    public function create(array $data)
    {
        return Booking::create($data);
    }

    public function update($id, array $data)
    {
        $booking = Booking::find($id);
        if ($booking) {
            $booking->update($data);
        }
        return $booking;
    }

    public function delete($id)
    {
        $booking = Booking::find($id);
        if ($booking) {
            $booking->delete();
        }
        return $booking;
    }
}
