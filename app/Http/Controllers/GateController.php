<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GateController extends Controller
{
    /**
     * Show the gate scanner interface.
     */
    public function scan(): View
    {
        return view('gate.scan');
    }

    /**
     * Verify and check in a ticket via QR or manual code.
     */
    public function checkIn(Request $request): JsonResponse
    {
        $request->validate(['ticket_code' => 'required|string']);

        $ticket = Ticket::with(['orderItem.ticketTier'])
            ->where('ticket_code', $request->ticket_code)
            ->first();

        if (! $ticket) {
            return response()->json([
                'status' => 'INVALID',
                'message' => 'Tiket tidak ditemukan',
            ], 404);
        }

        if ($ticket->is_used) {
            return response()->json([
                'status' => 'USED',
                'message' => 'Tiket sudah digunakan pada: '.$ticket->used_at->format('d M Y H:i:s'),
                'holder' => $ticket->holder_name,
            ], 409);
        }

        $ticket->update([
            'is_used' => true,
            'used_at' => now(),
        ]);

        return response()->json([
            'status' => 'VALID',
            'message' => 'Akses diterima! Selamat berpesta.',
            'holder' => $ticket->holder_name,
            'tier' => $ticket->orderItem->ticketTier->name,
        ], 200);
    }
}
