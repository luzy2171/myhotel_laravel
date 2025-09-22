<?php

namespace App\Http\Controllers\Receptionist;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
// PERBAIKAN: Menggunakan nama class lengkap untuk PDF agar lebih andal
use Barryvdh\DomPDF\Facade\Pdf;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Booking::with('guest', 'room')->latest();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('guest', function ($guestQuery) use ($search) {
                    $guestQuery->where('name', 'like', '%' . $search . '%');
                })->orWhereHas('room', function ($roomQuery) use ($search) {
                    $roomQuery->where('room_number', 'like', '%' . $search . '%');
                });
            });
        }

        $bookings = $query->paginate(10);
        return view('receptionist.bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $guests = Guest::orderBy('name')->get();
        $availableRooms = Room::where('status', 'available')->orderBy('room_number')->get();

        return view('receptionist.bookings.create', compact('guests', 'availableRooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'guest_id' => 'required|exists:guests,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
        ]);

        $booking = Booking::create([
            'guest_id' => $request->guest_id,
            'room_id' => $request->room_id,
            'check_in_date' => $request->check_in_date,
            'check_out_date' => $request->check_out_date,
            'status' => 'confirmed',
        ]);

        return redirect()->route('receptionist.bookings.index')->with('success', 'Booking baru berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Booking $booking)
    {
        $guests = Guest::orderBy('name')->get();
        $availableRooms = Room::where('status', 'available')->orWhere('id', $booking->room_id)->orderBy('room_number')->get();
        return view('receptionist.bookings.edit', compact('booking', 'guests', 'availableRooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'guest_id' => 'required|exists:guests,id',
            'room_id' => 'required|exists:rooms,id',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
        ]);

        $booking->update($request->all());
        return redirect()->route('receptionist.bookings.index')->with('success', 'Booking berhasil diperbarui.');
    }

    /**
     * Menampilkan halaman konfirmasi check-in dengan detail pembayaran.
     */
    public function showCheckinForm(Booking $booking)
    {
        if ($booking->status != 'confirmed') {
            return redirect()->route('receptionist.bookings.index')->with('error', 'Booking ini tidak dalam status untuk check-in.');
        }

        $checkIn = new Carbon($booking->check_in_date);
        $checkOut = new Carbon($booking->check_out_date);
        $nights = $checkIn->diffInDays($checkOut) ?: 1;
        $totalPrice = $nights * $booking->room->price_per_night;

        return view('receptionist.bookings.checkin', compact('booking', 'nights', 'totalPrice'));
    }

    /**
     * Memproses check-in dan pembayaran.
     */
    public function processCheckin(Request $request, Booking $booking)
    {
        $request->validate([
            'payment_method' => 'required|in:cash,debit,qris',
        ]);

        $checkIn = new Carbon($booking->check_in_date);
        $checkOut = new Carbon($booking->check_out_date);
        $nights = $checkIn->diffInDays($checkOut) ?: 1;
        $totalPrice = $nights * $booking->room->price_per_night;

        // Buat transaksi
        Transaction::create([
            'booking_id' => $booking->id,
            'user_id' => Auth::id(), // ID Resepsionis yang login
            'transaction_date' => Carbon::now(),
            'amount' => $totalPrice,
            'payment_method' => $request->payment_method,
            'description' => 'Pembayaran check-in untuk ' . $nights . ' malam.',
        ]);

        // Ubah status booking & kamar
        $booking->status = 'checked_in';
        $booking->save();

        $room = $booking->room;
        $room->status = 'occupied';
        $room->save();

        return redirect()->route('receptionist.bookings.index')->with('success', 'Tamu berhasil check-in dan pembayaran telah dicatat.');
    }

    /**
     * Memproses check-out (tanpa pembayaran).
     */
    public function processCheckout(Booking $booking)
    {
        $booking->status = 'checked_out';
        $booking->save();

        $room = $booking->room;
        $room->status = 'cleaning';
        $room->save();

        return redirect()->route('receptionist.bookings.index')->with('success', 'Tamu berhasil check-out.');
    }

    /**
     * Menghasilkan dan menampilkan invoice.
     */
    public function generateInvoice(Booking $booking)
    {
        if (!$booking->transaction) {
             return redirect()->route('receptionist.bookings.index')->with('error', 'Invoice tidak dapat dibuat karena transaksi tidak ditemukan.');
        }

        $checkIn = new Carbon($booking->check_in_date);
        $checkOut = new Carbon($booking->check_out_date);
        $nights = $checkIn->diffInDays($checkOut) ?: 1;
        $total = $booking->transaction->amount;

        $pdf = Pdf::loadView('receptionist.bookings.invoice', compact('booking', 'nights', 'total'));
        return $pdf->stream('invoice-'.$booking->id.'.pdf');
    }

    /**
     * Membatalkan booking.
     */
    public function cancel(Booking $booking)
    {
        if ($booking->status != 'confirmed') {
            return redirect()->back()->with('error', 'Hanya booking yang terkonfirmasi yang bisa dibatalkan.');
        }

        $booking->status = 'cancelled';
        $booking->save();

        return redirect()->route('receptionist.bookings.index')->with('success', 'Booking berhasil dibatalkan.');
    }
}

