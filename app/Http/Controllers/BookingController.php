<?php

namespace App\Http\Controllers;

use App\Models\TicketTier;
use App\Services\BookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(private readonly BookingService $bookingService) {}

    public function selectTier(int $tierId): View|RedirectResponse
    {
        $tier = TicketTier::with('event')->where('is_active', true)->findOrFail($tierId);

        if ($tier->isSoldOut()) {
            return redirect()->route('home')->with('error', 'Tiket untuk kategori ini sudah habis terjual.');
        }

        $event = $tier->event;

        return view('booking.checkout', compact('tier', 'event'));
    }

    public function processCheckout(Request $request): RedirectResponse
    {
        $quantity = (int) $request->input('quantity', 1);

        $holders = $request->input('holders', []);
        if (is_array($holders)) {
            $holders = array_slice($holders, 0, max(1, min(4, $quantity)));

            // If first holder is empty but customer_name is provided, fallback to customer_name
            if ((! isset($holders[0]) || trim((string) $holders[0]) === '') && $request->filled('customer_name')) {
                $holders[0] = (string) $request->input('customer_name');
            }
        }

        $input = $request->all();
        $input['holders'] = $holders;

        $tier = TicketTier::find($request->input('tier_id'));
        $maxAllowed = $tier ? min(4, max(1, $tier->available_stock)) : 4;

        $validated = validator($input, [
            'tier_id' => 'required|exists:ticket_tiers,id',
            'quantity' => "required|integer|min:1|max:{$maxAllowed}",
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'holders' => ['required', 'array', "size:{$quantity}"],
            'holders.*' => 'required|string|max:255',
        ], [
            'tier_id.required' => 'Kategori tiket harus dipilih.',
            'quantity.required' => 'Jumlah tiket harus ditentukan.',
            'quantity.min' => 'Minimal pembelian 1 tiket.',
            'quantity.max' => "Maksimal pembelian {$maxAllowed} tiket.",
            'customer_name.required' => 'Nama lengkap pemesan wajib diisi.',
            'customer_email.required' => 'Email pemesan wajib diisi.',
            'customer_email.email' => 'Format email pemesan tidak valid.',
            'customer_phone.required' => 'Nomor WhatsApp wajib diisi.',
            'holders.*.required' => 'Nama setiap pemegang tiket wajib diisi.',
            'holders.size' => 'Jumlah nama pemegang tiket harus sesuai dengan jumlah tiket (:size tiket).',
        ])->validate();

        try {
            $order = $this->bookingService->executeCheckout($validated);

            return redirect()->route('ticket.show', ['orderCode' => $order->order_code])
                ->with('success', 'Pembelian berhasil! Tiketmu sudah siap.');
        } catch (\RuntimeException $e) {
            return back()->withErrors(['quota' => $e->getMessage()])->withInput();
        }
    }
}
