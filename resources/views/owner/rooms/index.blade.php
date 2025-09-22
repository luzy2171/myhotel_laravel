@extends('layouts.app')

@section('title', 'Manajemen Kamar')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Kamar</h1>
        <a href="{{ route('owner.rooms.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
            + Tambah Kamar Baru
        </a>
    </div>

    @include('layouts.partials.alert')

    <div class="bg-white shadow-lg rounded-lg overflow-x-auto">
        <table class="min-w-full bg-white">
            <thead class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                <tr>
                    <th class="py-3 px-6 text-left">No. Kamar</th>
                    <th class="py-3 px-6 text-left">Tipe</th>
                    <th class="py-3 px-6 text-left">Harga/Malam</th>
                    <th class="py-3 px-6 text-center">Status</th>
                    <th class="py-3 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @forelse ($rooms as $room)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left whitespace-nowrap font-medium">{{ $room->room_number }}</td>
                    <td class="py-3 px-6 text-left">{{ $room->type }}</td>
                    <td class="py-3 px-6 text-left">Rp{{ number_format($room->price_per_night, 0, ',', '.') }}</td>
                    <td class="py-3 px-6 text-center">
                        <!-- (BARU) Form untuk update status -->
                        <form action="{{ route('owner.rooms.updateStatus', $room->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="border rounded px-2 py-1" onchange="this.form.submit()">
                                <option value="available" @if($room->status == 'available') selected @endif>Tersedia</option>
                                <option value="occupied" @if($room->status == 'occupied') selected @endif>Terisi</option>
                                <option value="maintenance" @if($room->status == 'maintenance') selected @endif>Perbaikan</option>
                                <option value="cleaning" @if($room->status == 'cleaning') selected @endif>Dibersihkan</option>
                            </select>
                        </form>
                    </td>
                    <td class="py-3 px-6 text-center">
                        <div class="flex item-center justify-center space-x-4">
                            <a href="{{ route('owner.rooms.edit', $room->id) }}" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" /><path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" /></svg>
                            </a>
                            <form action="{{ route('owner.rooms.destroy', $room->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus kamar ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-4">Tidak ada data kamar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $rooms->links() }}
    </div>
</div>
@endsection

