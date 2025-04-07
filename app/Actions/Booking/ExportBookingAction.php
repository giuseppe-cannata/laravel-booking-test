<?php

namespace App\Actions\Booking;

use App\Repositories\BookingRepository;
use League\Csv\Writer;

class ExportBookingAction
{
    protected $bookingRepository;

    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    /**
     * Esegue l'azione di esportazione delle prenotazioni in formato CSV.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function execute()
    {
        // Recupera tutte le prenotazioni con i dettagli del cliente
        $bookings = $this->bookingRepository->getAllWithCustomer();

        // Crea un nuovo oggetto CSV
        $csv = Writer::createFromString('');
        
        // Aggiunge l'intestazione al CSV
        $csv->insertOne(['ID', 'Customer Name', 'Email', 'Booking Date', 'Notes']);

        // Aggiunge ogni booking come una nuova riga nel CSV
        foreach ($bookings as $booking) {
            $csv->insertOne([
                $booking->id,
                $booking->customer->name ?? '',
                $booking->customer->email ?? '',
                $booking->booking_date,
                $booking->notes,
            ]);
        }

        // Restituisce il CSV come risposta, con il tipo di contenuto 'text/csv' e l'intestazione per il download del file
        return response($csv->toString(), 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="bookings.csv"',
        ]);
    }
}
