<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookingControllerTest extends TestCase
{
    use RefreshDatabase;

    private function authenticatedUser()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum'); // o semplicemente ->actingAs($user) se non usi Sanctum
        return $user;
    }

    public function test_can_list_bookings()
    {
        $this->authenticatedUser();

        Booking::factory()->count(3)->create();

        $response = $this->getJson('/api/bookings');

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    public function test_can_create_a_booking()
    {
        $this->authenticatedUser();

        $customer = Customer::factory()->create();

        $data = [
            'customer_id' => $customer->id,
            'booking_date' => now()->toDateTimeString(),
            'notes' => 'Test note',
        ];

        $response = $this->postJson('/api/bookings', $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('bookings', [
            'customer_id' => $customer->id,
            'notes' => 'Test note',
        ]);
    }

    public function test_cannot_create_booking_with_invalid_customer()
    {
        $this->authenticatedUser();

        $data = [
            'customer_id' => 999, // inesistente
            'booking_date' => now()->toDateTimeString(),
            'notes' => 'Invalid customer',
        ];

        $response = $this->postJson('/api/bookings', $data);

        $response->assertStatus(404);
        $response->assertJson(['error' => 'Cliente non trovato']);
    }

    public function test_can_show_a_booking()
    {
        $this->authenticatedUser();

        $booking = Booking::factory()->create();

        $response = $this->getJson("/api/bookings/{$booking->id}");

        $response->assertStatus(200);
        $response->assertJson(['id' => $booking->id]);
    }

    public function test_returns_404_if_booking_not_found()
    {
        $this->authenticatedUser();

        $response = $this->getJson('/api/bookings/999');

        $response->assertStatus(404);
    }

    public function test_can_update_booking()
    {
        // Autenticazione dell'utente (se non l'hai già implementata in un altro metodo)
        $this->authenticatedUser();

        // Crea un cliente fittizio per l'aggiornamento della prenotazione
        $customer = Customer::factory()->create();

        // Crea una prenotazione associata a quel cliente
        $booking = Booking::factory()->create([
            'customer_id' => $customer->id, // Associa il customer_id
        ]);

        // Esegui la richiesta di aggiornamento
        $response = $this->putJson("/api/bookings/{$booking->id}", [
            'customer_id' => $customer->id, // Includi customer_id nella richiesta
            'booking_date' => now()->addDay()->toDateTimeString(),
            'notes' => 'Aggiornato',
        ]);

        // Verifica la risposta (status 200 significa che l'aggiornamento è andato a buon fine)
        $response->assertStatus(200);

        // Verifica che i dati siano stati aggiornati correttamente nel database
        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'notes' => 'Aggiornato',
            'customer_id' => $customer->id, // Verifica anche il customer_id se necessario
        ]);
    }

    public function test_can_delete_booking()
    {
        $this->authenticatedUser();

        $booking = Booking::factory()->create();

        $response = $this->deleteJson("/api/bookings/{$booking->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('bookings', ['id' => $booking->id]);
    }
}
