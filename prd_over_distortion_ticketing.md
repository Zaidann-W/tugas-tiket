# Product Requirement Document (PRD)

## Over Distortion - Concert Ticketing System

## 1. Project Overview

* **Project Name:** Over Distortion Gig Ticketing Platform
* **Event Concept:** Special headline gig / showcase for **Over Distortion** (Dirty Punk Rock / Curva Rockers asal Sleman, Yogyakarta).
* **Tech Stack:** Laravel 11/12 (Blade/Livewire or Inertia), MySQL, Tailwind CSS, Alpine.js, Simple QrCode.
* **Target Audience:** Punk rock fans, Sleman football supporters / curva culture, event attendees, gig gatekeepers.

## 2. Band Identity & Content Assets

### 2.1 Band Profile
* **Band Name:** Over Distortion
* **Origin:** Sleman, D.I. Yogyakarta (Active since 2010)
* **Genre:** Dirty Punk Rock / Street Punk / Curva Rockers
* **Personnel:**
  * **Amek (Fahmi Muzaki):** Vocal & Guitar
  * **Kukun (Hasan Ashari):** Bass & Backing Vocal
  * **Angga Dipura:** Drums
* **Signature Tracks (for Landing Page Player/Setlist):**
  * *10 Tahun di Barisan*
  * *Sambut Hari Esok*
  * *Selebrasi*
  * *Sepak Bola Selalu*
  * *Where Love Is Home*

### 2.2 Visual & Media Asset Integration
* **Theme & Atmosphere:** Raw underground punk aesthetic, gritty halftone, heavy black, dark charcoal (`#0d0d0d`), accented with stadium flare red/crimson (`#e11d48`) and emerald stadium green (`#10b981`).
* **Photo Asset Slots in UI:**
  1. `hero_band_banner.jpg` (Hero Landing Section): Wide concert photo showing high-energy stage performance with smoke/flare atmosphere.
  2. `band_members_trio.jpg` (Band Profile Section): High-resolution band photo featuring Amek, Kukun, and Angga with short bio and role cards.
  3. `live_crowd_moshpit.jpg` (Venue & Experience Section): Crowd atmosphere showing energetic sing-along & moshpit.
  4. `ticket_card_bg.jpg` (E-Ticket Watermark): Monochromatic stamp/texture of Over Distortion logo and stage background.

## 3. Objectives & Success Metrics

### Objectives
1. Provide a fast, mobile-first ticket booking flow designed for gig-goers.
2. Prevent overselling on high-demand presale tiers using database row-level locking (`lockForUpdate()`).
3. Generate distinct, fraud-proof digital e-tickets containing cryptographically generated QR codes.
4. Provide gate crew with an instant scanner interface verifying tickets under 1.5 seconds.

### Success Metrics
* Ticket scanning turnaround time $\le 1.5$ seconds per attendee.
* Zero overselling on capped moshpit/tribune quota under high checkout concurrency.
* End-to-end checkout completion in $\le 3$ minutes.
* $100\%$ detection of duplicate or previously checked-in QR codes.

## 4. User Roles & Permissions

| Role | Access & Capabilities |
| :--- | :--- |
| **Guest / Fan** | Explore gig info, lineup, band photos, select ticket tiers (Presale/Festival), fill attendee info, complete checkout, view/download e-tickets. |
| **Gate Crew / Staf Pintu** | Access `/gate/scan`, scan QR codes via device camera or keyboard wedge, see real-time valid/invalid feedback, log entries. |
| **Admin Organiser** | Manage ticket tiers & quotas, observe real-time ticket sales metrics, track check-in headcount during event. |

## 5. Functional Specifications

### 5.1 Landing & Showcase Page
* **Hero Banner:** Headline *"OVER DISTORTION LIVE IN SLEMAN"*, date, venue (e.g., Maguwoharjo / GOR Sleman Area), CTA button *"Amankan Tiketmu"*.
* **Band Spotlight Section:** Features the photo of the trio (Amek, Kukun, Angga), short origin story, and discography highlights.
* **Audio Teaser / Playlist Embed:** Spotify / YouTube embed of top anthems (*10 Tahun Di Barisan*, *Sambut Hari Esok*).
* **Venue & Rules Guide:** Gate opening schedule, prohibited items (flares inside hall, weapons, glass bottles), and moshpit safety tips.

### 5.2 Ticket Tier Selection
* **Tiers & Quotas:**
  * *Early Bird - Barisan Depan* (Limited, Rp 35.000)
  * *Presale 1 - Festival Moshpit* (Rp 50.000)
  * *General Sale - Regular Curva Area* (Rp 65.000)
  * *VIP Pack (Ticket + Official Gig Poster & Lanyard)* (Rp 120.000)
* **Purchase Guard:** Maximum 4 tickets per checkout transaction.
* **Concurrency Locking:** Atomic checkout transaction preventing overselling when stock reaches $< 5$.

### 5.3 Checkout & Attendee Identification
* **Primary Contact:** Full Name, WhatsApp Number, Active Email Address.
* **Individual Holder Names:** Assign unique attendee name per ticket purchased to prevent resale.
* **Checkout Simulation:** Sandbox / Instant Payment Simulation toggle to mark orders as `paid` for local development and grading demo.

### 5.4 Digital E-Ticket & QR Generation
* **Format:** Unique alphanumeric code (e.g., `OD-2026-XXXXXX`) paired with signed QR Code string.
* **Visual Presentation:** Mobile-responsive pass styled like a gig wristband/ticket with Over Distortion typography, attendee name, tier name, and dynamic QR SVG.
* **Download:** Direct print/PDF view button.

### 5.5 Gate Scanner & Check-in System (`/gate/scan`)
* **Camera Scanner:** Built-in HTML5 QR camera scanner + manual barcode/text input field.
* **Real-time Responses:**
  * **200 Valid (Green Screen):** *"Akses Diterima - [Holder Name] - [Tier]"* -> Automatically updates `is_used = true` and `used_at = now()`.
  * **409 Conflict (Yellow Screen):** *"Peringatan: Tiket Sudah Digunakan pada [Timestamp]"*.
  * **404 Not Found (Red Screen):** *"Tiket Palsu / Tidak Terdaftar"*.

### 5.6 Admin Gate & Sales Monitoring
* Real-time metrics: Tickets Sold vs. Quota, Total Revenue, Total In-Venue (Check-in count) vs. Expected.

## 6. Database Schema & Architecture

### 6.1 Entity Relationships
* `Event` $1 \to N$ `TicketTier`
* `User` $1 \to N$ `Order`
* `Order` $1 \to N$ `OrderItem`
* `OrderItem` $1 \to N$ `Ticket`
* `TicketTier` $1 \to N$ `OrderItem`

### 6.2 Table Definitions

#### `users`
* `id` (bigint unsigned, PK)
* `name` (string)
* `email` (string, unique)
* `password` (string)
* `role` (enum: `'admin'`, `'gate_staff'`, `'customer'`, default: `'customer'`)
* `timestamps`

#### `events`
* `id` (bigint unsigned, PK)
* `title` (string, default: 'Over Distortion Showcase')
* `slug` (string, unique)
* `tagline` (string)
* `description` (text)
* `venue` (string)
* `event_date` (dateTime)
* `banner_image` (string, path to band photo)
* `timestamps`

#### `ticket_tiers`
* `id` (bigint unsigned, PK)
* `event_id` (foreignId -> events.id, cascade)
* `name` (string) - *e.g., 'Festival Moshpit'*
* `price` (unsignedInteger)
* `total_quota` (unsignedInteger)
* `available_stock` (unsignedInteger)
* `is_active` (boolean, default: true)
* `timestamps`

#### `orders`
* `id` (bigint unsigned, PK)
* `order_code` (string, unique, index) - *e.g., 'OD-ORD-2026-XXXX'*
* `user_id` (foreignId -> users.id, nullable)
* `customer_name` (string)
* `customer_email` (string)
* `customer_phone` (string)
* `total_amount` (unsignedInteger)
* `payment_status` (enum: `'pending'`, `'paid'`, `'expired'`, `'failed'`, default: `'pending'`)
* `paid_at` (dateTime, nullable)
* `timestamps`

#### `order_items`
* `id` (bigint unsigned, PK)
* `order_id` (foreignId -> orders.id, cascade)
* `ticket_tier_id` (foreignId -> ticket_tiers.id)
* `quantity` (unsignedSmallInteger)
* `unit_price` (unsignedInteger)
* `subtotal` (unsignedInteger)
* `timestamps`

#### `tickets`
* `id` (bigint unsigned, PK)
* `order_item_id` (foreignId -> order_items.id, cascade)
* `ticket_code` (string, unique, index) - *Secure random hash (UUID / Str::random(12))*
* `holder_name` (string)
* `is_used` (boolean, default: false)
* `used_at` (dateTime, nullable)
* `timestamps`

## 7. Critical Laravel Business Logic

### 7.1 Atomic Checkout with Row Locking
```php
namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Ticket;
use App\Models\TicketTier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingService
{
    public function executeCheckout(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            // Lock tier row to prevent race condition
            $tier = TicketTier::where('id', $data['tier_id'])->lockForUpdate()->firstOrFail();

            if ($tier->available_stock < $data['quantity']) {
                throw new \Exception('Tiket habis atau kuota tidak mencukupi.');
            }

            // Decrement remaining stock
            $tier->decrement('available_stock', $data['quantity']);

            // Create Master Order
            $order = Order::create([
                'order_code' => 'OD-' . date('Ymd') . '-' . strtoupper(Str::random(6)),
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'customer_phone' => $data['customer_phone'],
                'total_amount' => $tier->price * $data['quantity'],
                'payment_status' => 'paid', // Instant-paid for testing simulator
                'paid_at' => now(),
            ]);

            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'ticket_tier_id' => $tier->id,
                'quantity' => $data['quantity'],
                'unit_price' => $tier->price,
                'subtotal' => $tier->price * $data['quantity'],
            ]);

            // Issue individual tickets with unique QR hashes
            foreach ($data['holders'] as $holderName) {
                Ticket::create([
                    'order_item_id' => $orderItem->id,
                    'ticket_code' => 'TKT-OD-' . strtoupper(Str::random(10)),
                    'holder_name' => $holderName,
                    'is_used' => false,
                ]);
            }

            return $order;
        });
    }
}
```

### 7.2 Gate Verification Controller
```php
public function checkIn(Request $request)
{
    $request->validate(['ticket_code' => 'required|string']);

    $ticket = Ticket::with(['orderItem.ticketTier'])
        ->where('ticket_code', $request->ticket_code)
        ->first();

    if (!$ticket) {
        return response()->json(['status' => 'INVALID', 'message' => 'Tiket tidak ditemukan'], 404);
    }

    if ($ticket->is_used) {
        return response()->json([
            'status' => 'USED',
            'message' => 'Tiket sudah digunakan pada: ' . $ticket->used_at->format('d M Y H:i:s'),
            'holder' => $ticket->holder_name
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
        'tier' => $ticket->orderItem->ticketTier->name
    ], 200);
}
```

## 8. AntiGravity Development Checklist

- [ ] **Step 1:** Run `laravel new over-distortion-tickets` & set database credentials.
- [ ] **Step 2:** Generate migrations for `events`, `ticket_tiers`, `orders`, `order_items`, `tickets`.
- [ ] **Step 3:** Seed initial Over Distortion event data, member profiles, and ticket tiers.
- [ ] **Step 4:** Place Over Distortion concert images in `public/images/over-distortion/`.
- [ ] **Step 5:** Build dark punk landing UI with band bio, setlist teasers, and tier cards.
- [ ] **Step 6:** Build atomic booking logic (`lockForUpdate`).
- [ ] **Step 7:** Install `simplesoftwareio/simple-qrcode` and build public e-ticket view.
- [ ] **Step 8:** Build camera/manual QR validator page on `/gate/scan`.