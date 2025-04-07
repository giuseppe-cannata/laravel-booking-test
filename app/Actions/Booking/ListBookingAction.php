<?php

namespace App\Actions\Booking;

use App\Repositories\BookingRepository;

class ListBookingAction
{
    protected $bookingRepository;

    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function execute()
    {
        return $this->bookingRepository->getAllWithCustomer();
    }
}
