<?php

namespace App\Actions\Booking;

use App\Repositories\BookingRepository;

class CreateBookingAction
{
    protected $bookingRepository;

    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function execute(array $data)
    {
        return $this->bookingRepository->create($data);
    }
}
