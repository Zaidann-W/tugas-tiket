<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ticket;
use App\Models\TicketTier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingService
{
    /**
     * Execute an atomic checkout, preventing overselling via row-level locking.
     *
     * @param array{
     *     tier_id: int,
     *     quantity: int,
     *     customer_name: string,
     *     customer_email: string,
     *     customer_phone: string,
     *     holders: list<string>
     * } $data
     */
    public function executeCheckout(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            // Lock tier row to prevent race condition
            $tier = TicketTier::where('id', $data['tier_id'])->lockForUpdate()->firstOrFail();

            if ($tier->available_stock < $data['quantity']) {
                throw new \RuntimeException('Tiket habis atau kuota tidak mencukupi.');
            }

            // Decrement remaining stock atomically
            $tier->decrement('available_stock', $data['quantity']);

            // Create Master Order with instant-paid simulation
            $order = Order::create([
                'order_code' => 'OD-'.date('Ymd').'-'.strtoupper(Str::random(6)),
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'total_amount' => $tier->price * $data['quantity'],
                'payment_status' => 'paid',
                'paid_at' => now(),
            ]);

            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'ticket_tier_id' => $tier->id,
                'quantity' => $data['quantity'],
                'unit_price' => $tier->price,
                'subtotal' => $tier->price * $data['quantity'],
            ]);

            // Issue individual tickets with unique QR codes
            foreach ($data['holders'] as $holderName) {
                Ticket::create([
                    'order_item_id' => $orderItem->id,
                    'ticket_code' => 'TKT-OD-'.strtoupper(Str::random(10)),
                    'holder_name' => $holderName,
                    'is_used' => false,
                ]);
            }

            return $order;
        });
    }
}
