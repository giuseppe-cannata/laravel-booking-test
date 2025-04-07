<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Customer;
use App\Services\BookingService;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    protected $bookingService;

    /**
     * Crea una nuova istanza del controller di BookingController.
     * Il costruttore inietta il servizio di prenotazione nel controller.
     *
     * @return void
     */
    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }

    /**
     * Recupera tutte le prenotazioni.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        // Otteniamo tutte le prenotazioni usando il metodo list() dal servizio
        $bookings = $this->bookingService->list();

        return response()->json($bookings);
    }

    /**
     * Crea una nuova prenotazione.
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(StoreBookingRequest $request)
    {
        // Verifica se il cliente esiste
        $customer = Customer::find($request->input('customer_id'));

        if (! $customer) {
            // Se il cliente non esiste, restituisci un errore 404
            return response()->json(['error' => 'Cliente non trovato'], 404);
        }
        // Crea una nuova prenotazione utilizzando i dati validati
        $booking = $this->bookingService->create($request->validated());

        // Registra un log per la creazione della prenotazione
        Log::info('Prenotazione creata', ['booking' => $booking->id]);

        // Restituisce la prenotazione creata con un codice di stato 201 (creato)
        return response()->json($booking, 201);
    }

    /**
     * Recupera i dettagli di una prenotazione specifica.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // Recupera la prenotazione tramite il servizio
        $booking = $this->bookingService->show($id);

        // Se la prenotazione non esiste, restituisce un errore 404
        if (! $booking) {
            return response()->json(['message' => 'Prenotazione non trovata'], 404);
        }

        // Restituisce i dettagli della prenotazione
        return response()->json($booking);
    }

    /**
     * Aggiorna una prenotazione esistente.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(UpdateBookingRequest $request, $id)
    {
        // Aggiorna la prenotazione con i dati validati
        $booking = $this->bookingService->update($id, $request->validated());

        // Se la prenotazione non esiste, restituisce un errore 404
        if (! $booking) {
            return response()->json(['message' => 'Prenotazione non trovata'], 404);
        }

        // Registra un log per l'aggiornamento della prenotazione
        Log::info('Prenotazione aggiornata', ['booking' => $booking->id]);

        // Restituisce i dettagli della prenotazione aggiornata
        return response()->json($booking);
    }

    /**
     * Elimina una prenotazione esistente.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Elimina la prenotazione tramite il servizio
        $success = $this->bookingService->delete($id);

        // Se la prenotazione non esiste, restituisce un errore 404
        if (! $success) {
            return response()->json(['message' => 'Prenotazione non trovata'], 404);
        }

        // Registra un log per la cancellazione della prenotazione
        Log::warning('Prenotazione cancellata', ['booking_id' => $id]);

        // Restituisce una risposta di successo
        return response()->json(['message' => 'Prenotazione eliminata con successo']);
    }

    /**
     * Esporta tutte le prenotazioni in un file CSV.
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        // Esporta le prenotazioni in formato CSV
        $csv = $this->bookingService->export();

        // Restituisce il CSV come risposta con intestazioni per il download
        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="bookings.csv"',
        ]);
    }
}
