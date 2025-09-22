@extends('layouts.app')

@section('title', 'Manajemen Status Kamar')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Status Kamar</h1>
    </div>

    @include('layouts.partials.alert')

    <div class="bg-white shadow-lg rounded-lg overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <tr>
                    <th class="py-3 px-6 text-left">No. Kamar</th>
                    <th class="py-3 px-6 text-left">Tipe</th>
                    <th class="py-3 px-6 text-center">Status Saat Ini</th>
                    <th class="py-3 px-6 text-center" style="width: 20%;">Ubah Status</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @forelse ($rooms as $room)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left whitespace-nowrap font-medium">{{ $room->room_number }}</td>
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
                                {{-- Status 'maintenance' tidak bisa diatur oleh resepsionis --}}
                                @if($room->status == 'maintenance')
                                    <option value="maintenance" selected disabled>Perbaikan</option>
                                @endif
                            </select>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-4 px-6 text-center text-gray-500">Tidak ada data kamar yang ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- Link Pagination -->
    <div class="mt-6">
        {{ $rooms->links() }}
    </div>
</div>
@endsection
