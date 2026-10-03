<?php

use App\Models\Event;
use App\Models\TicketTier;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->event = Event::create([
        'title' => 'Over Distortion Live in Sleman',
        'slug' => 'over-distortion-live-in-sleman',
        'tagline' => 'Dirty Punk Rock Sleman',
        'description' => 'Test gig',
        'venue' => 'Gor Tridadi Sleman',
        'event_date' => now()->addDays(10),
        'is_active' => true,
    ]);

    $this->tier = TicketTier::create([
        'event_id' => $this->event->id,
        'name' => 'Presale - Festival Moshpit',
        'price' => 75000,
        'total_quota' => 100,
        'available_stock' => 100,
        'is_active' => true,
    ]);
});

test('checkout works successfully with 1 ticket and extra empty holders from browser form', function () {
    $response = $this->post(route('booking.process'), [
        'tier_id' => $this->tier->id,
        'quantity' => 1,
        'customer_name' => 'Budi Santoso',
        'customer_email' => 'budi@example.com',
        'customer_phone' => '08123456789',
        'holders' => ['Budi Santoso', '', '', ''],
    ]);

    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('orders', [
        'customer_email' => 'budi@example.com',
        'payment_status' => 'paid',
    ]);
    $this->assertDatabaseHas('tickets', [
        'holder_name' => 'Budi Santoso',
    ]);
});

test('checkout works with multiple tickets and fills all holders', function () {
    $response = $this->post(route('booking.process'), [
        'tier_id' => $this->tier->id,
        'quantity' => 2,
        'customer_name' => 'Amek Punk',
        'customer_email' => 'amek@example.com',
        'customer_phone' => '08123456789',
        'holders' => ['Amek Punk', 'Kukun Bass', '', ''],
    ]);

    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('orders', [
        'customer_email' => 'amek@example.com',
        'total_amount' => 150000,
    ]);
    $this->assertDatabaseHas('tickets', ['holder_name' => 'Amek Punk']);
    $this->assertDatabaseHas('tickets', ['holder_name' => 'Kukun Bass']);
});

test('checkout auto-fills holder 1 with customer name if left blank', function () {
    $response = $this->post(route('booking.process'), [
        'tier_id' => $this->tier->id,
        'quantity' => 1,
        'customer_name' => 'Siti Rahayu',
        'customer_email' => 'siti@example.com',
        'customer_phone' => '08123456789',
        'holders' => ['', '', '', ''],
    ]);

    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('tickets', [
        'holder_name' => 'Siti Rahayu',
    ]);
});
