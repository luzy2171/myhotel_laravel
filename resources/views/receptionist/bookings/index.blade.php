@extends('layouts.app')

@section('title', 'Manajemen Booking')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Booking</h1>
        <a href="{{ route('receptionist.bookings.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
            + Booking Baru
        </a>
    </div>

    @include('layouts.partials.alert')

    <!-- Form Pencarian -->
    <div class="mb-4">
        <form action="{{ route('receptionist.bookings.index') }}" method="GET">
            <div class="flex">
                <input type="text" name="search" class="w-full border rounded-l-lg p-2" placeholder="Cari nama tamu atau nomor kamar..." value="{{ request('search') }}">
                <button type="submit" class="bg-blue-600 text-white p-2 rounded-r-lg">Cari</button>
            </div>
        </form>
    </div>

    <div class="bg-white shadow-lg rounded-lg overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <tr>
                    <th class="py-3 px-6 text-left">Tamu</th>
                    <th class="py-3 px-6 text-left">Kamar</th>
                    <th class="py-3 px-6 text-center">Check-in</th>
                    <th class="py-3 px-6 text-center">Check-out</th>
                    <th class="py-3 px-6 text-center">Status</th>
                    <th class="py-3 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @forelse ($bookings as $booking)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left whitespace-nowrap">{{ $booking->guest->name }}</td>
                    <td class="py-3 px-6 text-left">{{ $booking->room->room_number }} ({{ $booking->room->type }})</td>
                    <td class="py-3 px-6 text-center">{{ \Carbon\Carbon::parse($booking->check_in_date)->format('d M Y') }}</td>
                    <td class="py-3 px-6 text-center">{{ \Carbon\Carbon::parse($booking->check_out_date)->format('d M Y') }}</td>
                    <td class="py-3 px-6 text-center">
                        <span class="py-1 px-3 rounded-full text-xs font-semibold
                            @if($booking->status == 'confirmed') bg-yellow-200 text-yellow-800 @endif
                            @if($booking->status == 'checked_in') bg-blue-200 text-blue-800 @endif
                            @if($booking->status == 'checked_out') bg-green-200 text-green-800 @endif
                            @if($booking->status == 'cancelled') bg-gray-300 text-gray-800 @endif
                        ">{{ ucfirst(str_replace('_', ' ', $booking->status)) }}</span>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <div class="flex item-center justify-center space-x-2">
                            @if($booking->status == 'confirmed')
                                <a href="{{ route('receptionist.bookings.checkin.form', $booking->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-xs">Check-in</a>
                                <a href="{{ route('receptionist.bookings.edit', $booking->id) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded text-xs">Edit</a>
                                <form action="{{ route('receptionist.bookings.cancel', $booking->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin membatalkan booking ini?');">
                                    @csrf
                                    <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-1 px-3 rounded text-xs">Batal</button>
                                </form>
                            @elseif($booking->status == 'checked_in')
                                <form action="{{ route('receptionist.bookings.checkout', $booking->id) }}" method="POST" onsubmit="return confirm('Konfirmasi check-out untuk tamu ini?');">
                                    @csrf
                                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-xs">Check-out</button>
                                </form>
                            @elseif($booking->status == 'checked_out')
                                {{-- (BARU) Tombol untuk melihat invoice setelah check-out --}}
                                <a href="{{ route('receptionist.bookings.invoice', $booking->id) }}" target="_blank" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-1 px-3 rounded text-xs">
                                    Invoice
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-3 px-6 text-center">Tidak ada data booking ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">
        {{ $bookings->links() }}
    </div>
</div>
@endsection

