<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\TicketTier;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Show the admin monitoring dashboard.
     */
    public function dashboard(): View
    {
        $event = Event::with(['ticketTiers'])->first();

        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $totalOrders = Order::where('payment_status', 'paid')->count();
        $totalTicketsSold = Ticket::count();
        $totalCheckedIn = Ticket::where('is_used', true)->count();

        $tierStats = TicketTier::withCount([
            'orderItems as tickets_sold' => function ($query) {
                $query->selectRaw('SUM(quantity)');
            },
        ])->get();

        $recentOrders = Order::with('orderItems.ticketTier')
            ->where('payment_status', 'paid')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'event',
            'totalRevenue',
            'totalOrders',
            'totalTicketsSold',
            'totalCheckedIn',
            'tierStats',
            'recentOrders',
        ));
    }
}
