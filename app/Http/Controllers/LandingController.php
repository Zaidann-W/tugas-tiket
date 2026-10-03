<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\View\View;

class LandingController extends Controller
{
    public function index(): View
    {
        $event = Event::with(['ticketTiers' => function ($query) {
            $query->where('is_active', true)->orderBy('price');
        }])->first();

        return view('landing', compact('event'));
    }
}
