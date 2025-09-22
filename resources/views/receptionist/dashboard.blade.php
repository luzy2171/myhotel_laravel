@extends('layouts.app')

@section('title', 'Dashboard Resepsionis')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Dashboard Resepsionis</h1>

    <!-- Tampilan Status Kamar Visual & Interaktif -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Kamar Saat Ini</h3>
        <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-4">
            {{-- PERBAIKAN: Menggunakan variabel $rooms yang benar --}}
            @foreach($rooms as $room)
                @php
                    $statusClass = '';
                    $tooltip = 'Status: ' . ucfirst($room->status);
                    $link = '#';
                    $cursor = 'cursor-pointer';

                    switch ($room->status) {
                        case 'available':
                            $statusClass = 'bg-green-100 border-green-500 text-green-700 hover:bg-green-200';
                            $link = route('receptionist.bookings.create', ['room_id' => $room->id]);
                            break;
                        case 'occupied':
                            $statusClass = 'bg-red-100 border-red-500 text-red-700 hover:bg-red-200';
                            $bookingInfo = $room->bookings->where('status', 'checked_in')->first();
                            if ($bookingInfo && $bookingInfo->guest) {
                                $tooltip = 'Tamu: ' . $bookingInfo->guest->name . ' | Check-out: ' . \Carbon\Carbon::parse($bookingInfo->check_out_date)->format('d M Y');
                            }
                            break;
                        case 'cleaning':
                            $statusClass = 'bg-blue-100 border-blue-500 text-blue-700';
                            $cursor = 'cursor-not-allowed';
                            break;
                        case 'maintenance':
                            $statusClass = 'bg-gray-200 border-gray-500 text-gray-700';
                            $cursor = 'cursor-not-allowed';
                            break;
                    }
                @endphp
                <a href="{{ $link }}" title="{{ $tooltip }}" class="block p-4 border-l-4 rounded-lg shadow-sm {{ $statusClass }} {{ $cursor }} transition-transform transform hover:scale-105">
                    <div class="font-bold text-lg">{{ $room->room_number }}</div>
                    <div class="text-sm">{{ $room->type }}</div>
                </a>
            @endforeach
        </div>
    </div>

    <!-- (DIKEMBALIKAN) Tabel Manajemen Status Kamar -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Manajemen Status Kamar</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-gray-600 uppercase">No. Kamar</th>
                        <th class="py-3 px-6 text-left text-xs font-semibold text-gray-600 uppercase">Tipe</th>
                        <th class="py-3 px-6 text-center text-xs font-semibold text-gray-600 uppercase">Status Saat Ini</th>
                        <th class="py-3 px-6 text-center text-xs font-semibold text-gray-600 uppercase">Ubah Status</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm">
                    @forelse ($paginatedRooms as $room)
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="py-3 px-6 text-left font-medium">{{ $room->room_number }}</td>
                        <td class="py-3 px-6 text-left">{{ $room->type }}</td>
                        <td class="py-3 px-6 text-center">
                            <span class="py-1 px-3 rounded-full text-xs font-semibold
                                @if($room->status == 'available') bg-green-200 text-green-800 @endif
                                @if($room->status == 'occupied') bg-red-200 text-red-800 @endif
                                @if($room->status == 'cleaning') bg-blue-200 text-blue-800 @endif
                                @if($room->status == 'maintenance') bg-yellow-200 text-yellow-800 @endif
                            ">{{ ucfirst($room->status) }}</span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <form action="{{ route('receptionist.rooms.updateStatus', $room->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="border rounded px-2 py-1 bg-white" onchange="this.form.submit()">
                                    <option value="available" @if($room->status == 'available') selected @endif>Tersedia</option>
                                    <option value="occupied" @if($room->status == 'occupied') selected @endif>Terisi</option>
                                    <option value="cleaning" @if($room->status == 'cleaning') selected @endif>Sedang Dibersihkan</option>
                                    @if($room->status == 'maintenance')
                                        <option value="maintenance" selected disabled>Perbaikan</option>
                                    @endif
                                </select>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">Tidak ada data kamar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $paginatedRooms->links() }}
        </div>
    </div>

    <!-- Daftar Check-in dan Check-out -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Daftar Check-in -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Jadwal Check-in Hari Ini</h3>
            @forelse($checkInsToday as $booking)
                <div class="border-b py-2">
                    <p class="font-semibold">{{ $booking->guest->name }} - Kamar {{ $booking->room->room_number }}</p>
                    <p class="text-sm text-gray-500">Status: <span class="font-medium text-yellow-600">{{ $booking->status }}</span></p>
                </div>
            @empty
                <p class="text-gray-500">Tidak ada jadwal check-in hari ini.</p>
            @endforelse
        </div>
        <!-- Daftar Check-out -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Jadwal Check-out Hari Ini</h3>
            @forelse($checkOutsToday as $booking)
                <div class="border-b py-2">
                    <p class="font-semibold">{{ $booking->guest->name }} - Kamar {{ $booking->room->room_number }}</p>
                    <p class="text-sm text-gray-500">Status: <span class="font-medium text-blue-600">{{ $booking->status }}</span></p>
                </div>
            @empty
                <p class="text-gray-500">Tidak ada jadwal check-out hari ini.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection

