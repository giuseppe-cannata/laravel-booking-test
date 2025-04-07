<?php

namespace App\Actions\Booking;

use App\Repositories\BookingRepository;

class DeleteBookingAction
{
    protected $bookingRepository;

    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function execute($id)
    {
        return $this->bookingRepository->delete($id);
    }
}
