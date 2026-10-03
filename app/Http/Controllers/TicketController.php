<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Ticket;
use Illuminate\View\View;

class TicketController extends Controller
{
    /**
     * Show all tickets for a given order code.
     */
    public function show(string $orderCode): View
    {
        $order = Order::where('order_code', $orderCode)
            ->with(['orderItems.tickets', 'orderItems.ticketTier', 'orderItems.ticketTier.event'])
            ->firstOrFail();

        return view('ticket.show', compact('order'));
    }

    /**
     * Show a single ticket for printing/download.
     */
    public function single(string $ticketCode): View
    {
        $ticket = Ticket::where('ticket_code', $ticketCode)
            ->with(['orderItem.ticketTier.event', 'orderItem.order'])
            ->firstOrFail();

        return view('ticket.single', compact('ticket'));
    }
}
