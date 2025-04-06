<?php

namespace App\Http\Controllers\Api;

use League\Csv\Writer; // Importa la libreria League\Csv per la gestione dei file CSV
use App\Models\Booking; // Importa il modello Booking
use Illuminate\Support\Facades\Log; // Importa il Log per registrare le informazioni di log
use App\Http\Controllers\Controller; // Importa il controller base di Laravel
use App\Http\Requests\StoreBookingRequest; // Importa la richiesta di validazione per la creazione di booking
use App\Http\Requests\UpdateBookingRequest; // Importa la richiesta di validazione per l'aggiornamento di booking

class BookingController extends Controller
{
    /**
     * Ottiene tutti i booking e carica la relazione 'customer'.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Recupera tutti i booking e carica i dati del customer associato
        return Booking::with('customer')->get();
    }

    /**
     * Crea un nuovo booking.
     *
     * @param  \App\Http\Requests\StoreBookingRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBookingRequest $request)
    {
        // Crea un nuovo booking utilizzando i dati validati dalla richiesta
        $booking = Booking::create($request->validated());

        // Registra un log per il booking appena creato
        Log::info('Booking created', ['booking' => $booking->id]);

        // Restituisce una risposta JSON con il nuovo booking creato e codice 201 (Created)
        return response()->json($booking, 201);
    }

    /**
     * Mostra un booking specifico in base al suo ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Trova il booking con l'ID specificato
        $booking = Booking::find($id);

        // Se il booking non esiste, restituisce un errore 404
        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        // Restituisce il booking con la relazione 'customer' caricata
        return $booking->load('customer');
    }

    /**
     * Aggiorna un booking esistente.
     *
     * @param  \App\Http\Requests\UpdateBookingRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBookingRequest $request, $id)
    {
        // Cerca il booking da aggiornare utilizzando l'ID
        $booking = Booking::find($id);

        // Se il booking non esiste, restituisce un errore 404
        if (!$booking) {
            return response()->json(['message' => 'Booking non trovato.'], 404);
        }

        // Aggiorna il booking con i dati validati dalla richiesta
        $booking->update($request->validated());

        // Registra un log per l'aggiornamento del booking
        Log::info('Booking aggiornato', ['booking' => $booking->id]);

        // Restituisce una risposta JSON con i dati aggiornati del booking
        return response()->json($booking);
    }

    /**
     * Elimina un booking esistente.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Trova il booking da eliminare utilizzando l'ID
        $booking = Booking::find($id);

        // Se il booking non esiste, restituisce un errore 404
        if (!$booking) {
            return response()->json(['message' => 'Booking non trovato.'], 404);
        }

        // Elimina il booking dal database
        $booking->delete();

        // Registra un log per la cancellazione del booking
        Log::warning('Booking deleted', ['booking' => $booking->id]);

        // Restituisce una risposta JSON con un messaggio di successo
        return response()->json(['message' => 'Booking eliminato con successo.']);
    }

    /**
     * Esporta tutti i booking in un file CSV.
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        // Recupera tutti i booking con la relazione 'customer' caricata
        $bookings = Booking::with('customer')->get();

        // Crea un nuovo oggetto CSV
        $csv = Writer::createFromString('');
        
        // Aggiunge l'intestazione al CSV
        $csv->insertOne(['ID', 'Customer Name', 'Email', 'Booking Date', 'Notes']);

        // Aggiunge ogni booking come una nuova riga nel CSV
        foreach ($bookings as $booking) {
            $csv->insertOne([
                $booking->id,
                $booking->customer->name,
                $booking->customer->email,
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
