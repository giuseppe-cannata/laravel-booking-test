<?php

namespace App\Actions\Booking;

use App\Repositories\BookingRepository;

class ShowBookingAction
{
    protected $bookingRepository;

    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function execute($bookingId)
    {
        return $this->bookingRepository->findById($bookingId);
    }
}
