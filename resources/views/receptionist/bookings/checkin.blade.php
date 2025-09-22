@extends('layouts.app')

@section('title', 'Konfirmasi Check-in')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Konfirmasi Check-in</h1>
        <p class="text-gray-600 mb-6">Anda akan melakukan check-in untuk tamu berikut. Mohon periksa kembali detail dan proses pembayaran di bawah ini sebelum melanjutkan.</p>

        <!-- Detail Booking -->
        <div class="bg-gray-50 p-4 rounded-md border border-gray-200 mb-6 space-y-2">
            <div class="flex justify-between">
                <span class="font-semibold text-gray-700">Nama Tamu:</span>
                <span class="text-gray-900">{{ $booking->guest->name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-semibold text-gray-700">Nomor Kamar:</span>
                <span class="text-gray-900">{{ $booking->room->room_number }} ({{ $booking->room->type }})</span>
            </div>
            <div class="flex justify-between">
                <span class="font-semibold text-gray-700">Tanggal Check-in:</span>
                <span class="text-gray-900">{{ \Carbon\Carbon::parse($booking->check_in_date)->isoFormat('D MMMM Y') }}</span>
            </div>
             <div class="flex justify-between">
                <span class="font-semibold text-gray-700">Tanggal Check-out:</span>
                <span class="text-gray-900">{{ \Carbon\Carbon::parse($booking->check_out_date)->isoFormat('D MMMM Y') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="font-semibold text-gray-700">Jumlah Malam:</span>
                <span class="text-gray-900">{{ $nights }} Malam</span>
            </div>
             <div class="border-t pt-2 mt-2 flex justify-between items-center">
                <span class="text-lg font-bold text-gray-800">Total Tagihan:</span>
                <span class="text-2xl font-bold text-blue-600">Rp{{ number_format($totalPrice, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Form Proses Check-in -->
        <form action="{{ route('receptionist.bookings.checkin.process', $booking->id) }}" method="POST">
            @csrf

            <!-- Metode Pembayaran -->
            <div class="mb-6">
                <label for="payment_method" class="block text-gray-700 text-sm font-bold mb-2">Metode Pembayaran:</label>
                <select name="payment_method" id="payment_method" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <option value="cash">Cash</option>
                    <option value="debit">Debit</option>
                    <option value="qris">QRIS</option>
                </select>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-center justify-end">
                <a href="{{ route('receptionist.bookings.index') }}" class="text-gray-600 hover:text-gray-800 font-bold py-2 px-4 mr-4">Batal</a>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                    Konfirmasi Check-in & Pembayaran
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

