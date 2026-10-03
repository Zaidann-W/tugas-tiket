<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ticket;
use App\Models\TicketTier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin Over Distortion',
            'email' => 'admin@overdistortion.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create gate staff user
        User::create([
            'name' => 'Gate Crew 01',
            'email' => 'gate@overdistortion.id',
            'password' => Hash::make('password'),
            'role' => 'gate_staff',
        ]);

        // Create the main event
        $event = Event::create([
            'title' => 'Over Distortion Live in Sleman',
            'slug' => 'over-distortion-live-sleman-2026',
            'tagline' => 'Satu Malam, Satu Barisan, Satu Distorsi.',
            'description' => 'Over Distortion, band Dirty Punk Rock / Street Punk asal Sleman, Yogyakarta yang aktif sejak 2010, hadir dalam sebuah showcase spesial yang akan menggetarkan GOR Area Sleman. Bersama Amek (Vocal & Guitar), Kukun (Bass & Backing Vocal), dan Angga Dipura (Drums), malam ini akan menjadi perayaan bagi semua barisan—punk rockers, curva warriors, dan siapapun yang menyimpan api di dada. Lagu-lagu anthemic dari 10 Tahun di Barisan, Sambut Hari Esok, Selebrasi, hingga Sepak Bola Selalu akan dimainkan penuh tenaga. Ini bukan sekadar gig. Ini adalah ritual.',
            'venue' => 'GOR Amongraga, Jl. Kenari No.1, Semaki, Umbulharjo, Kota Yogyakarta',
            'event_date' => '2026-11-15 19:00:00',
            'banner_image' => 'images/over-distortion/hero_band_banner.jpg',
        ]);

        // Create ticket tiers
        $earlyBird = TicketTier::create([
            'event_id' => $event->id,
            'name' => 'Early Bird - Barisan Depan',
            'price' => 35000,
            'total_quota' => 50,
            'available_stock' => 3, // almost sold out for demo
            'is_active' => true,
        ]);

        $presale = TicketTier::create([
            'event_id' => $event->id,
            'name' => 'Presale 1 - Festival Moshpit',
            'price' => 50000,
            'total_quota' => 200,
            'available_stock' => 147,
            'is_active' => true,
        ]);

        $regular = TicketTier::create([
            'event_id' => $event->id,
            'name' => 'General Sale - Regular Curva Area',
            'price' => 65000,
            'total_quota' => 500,
            'available_stock' => 492,
            'is_active' => true,
        ]);

        $vip = TicketTier::create([
            'event_id' => $event->id,
            'name' => 'VIP Pack (Tiket + Poster & Lanyard)',
            'price' => 120000,
            'total_quota' => 50,
            'available_stock' => 38,
            'is_active' => true,
        ]);

        // Create some demo orders and tickets
        $demoOrders = [
            [
                'customer_name' => 'Budi Santoso',
                'customer_email' => 'budi@example.com',
                'customer_phone' => '081234567890',
                'tier' => $presale,
                'holders' => ['Budi Santoso', 'Siti Rahayu'],
            ],
            [
                'customer_name' => 'Eko Prasetyo',
                'customer_email' => 'eko@example.com',
                'customer_phone' => '082345678901',
                'tier' => $regular,
                'holders' => ['Eko Prasetyo'],
            ],
        ];

        foreach ($demoOrders as $demo) {
            $tier = $demo['tier'];
            $qty = count($demo['holders']);

            $order = Order::create([
                'order_code' => 'OD-'.date('Ymd').'-'.strtoupper(Str::random(6)),
                'customer_name' => $demo['customer_name'],
                'customer_email' => $demo['customer_email'],
                'customer_phone' => $demo['customer_phone'],
                'total_amount' => $tier->price * $qty,
                'payment_status' => 'paid',
                'paid_at' => now()->subHours(rand(1, 48)),
            ]);

            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'ticket_tier_id' => $tier->id,
                'quantity' => $qty,
                'unit_price' => $tier->price,
                'subtotal' => $tier->price * $qty,
            ]);

            foreach ($demo['holders'] as $holderName) {
                Ticket::create([
                    'order_item_id' => $orderItem->id,
                    'ticket_code' => 'TKT-OD-'.strtoupper(Str::random(10)),
                    'holder_name' => $holderName,
                    'is_used' => false,
                ]);
            }

            $tier->decrement('available_stock', $qty);
        }
    }
}
