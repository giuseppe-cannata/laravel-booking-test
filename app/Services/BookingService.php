<?php

namespace App\Services;

use App\Actions\Booking\CreateBookingAction;
use App\Actions\Booking\DeleteBookingAction;
use App\Actions\Booking\ExportBookingAction;
use App\Actions\Booking\ListBookingAction;
use App\Actions\Booking\ShowBookingAction;
use App\Actions\Booking\UpdateBookingAction;

class BookingService
{
    protected $listBookingAction;

    protected $createBookingAction;

    protected $updateBookingAction;

    protected $deleteBookingAction;

    protected $showBookingAction;

    protected $exportBookingAction;

    /**
     * Crea una nuova istanza del servizio di BookingService.
     * Il costruttore inietta le azioni nel servizio.
     *
     * @return void
     */
    public function __construct(
        ListBookingAction $listBookingAction,
        CreateBookingAction $createBookingAction,
        UpdateBookingAction $updateBookingAction,
        DeleteBookingAction $deleteBookingAction,
        ShowBookingAction $showBookingAction,
        ExportBookingAction $exportBookingAction
    ) {
        $this->listBookingAction = $listBookingAction;
        $this->createBookingAction = $createBookingAction;
        $this->updateBookingAction = $updateBookingAction;
        $this->deleteBookingAction = $deleteBookingAction;
        $this->showBookingAction = $showBookingAction;
        $this->exportBookingAction = $exportBookingAction;
    }

    /**
     * Recupera tutte le prenotazioni.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function list()
    {
        return $this->listBookingAction->execute();
    }

    /**
     * Crea una nuova prenotazione.
     *
     * @return \App\Models\Booking
     */
    public function create(array $data)
    {
        return $this->createBookingAction->execute($data);
    }

    /**
     * Aggiorna una prenotazione esistente.
     *
     * @param  int  $id
     * @return \App\Models\Booking
     */
    public function update($id, array $data)
    {
        return $this->updateBookingAction->execute($id, $data);
    }

    /**
     * Elimina una prenotazione esistente.
     *
     * @param  int  $id
     * @return bool
     */
    public function delete($id)
    {
        return $this->deleteBookingAction->execute($id);
    }

    /**
     * Recupera i dettagli di una prenotazione.
     *
     * @param  int  $id
     * @return \App\Models\Booking
     */
    public function show($id)
    {
        return $this->showBookingAction->execute($id);
    }

    /**
     * Esporta tutte le prenotazioni in un file CSV.
     */
    public function export(): string
    {
        return $this->exportBookingAction->execute();
    }
}
