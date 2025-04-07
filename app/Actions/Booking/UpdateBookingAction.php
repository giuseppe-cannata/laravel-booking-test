<?php

namespace App\Actions\Booking;

use App\Repositories\BookingRepository;

class UpdateBookingAction
{
    protected $bookingRepository;

    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function execute($id, array $data)
    {
        return $this->bookingRepository->update($id, $data);
    }
}
