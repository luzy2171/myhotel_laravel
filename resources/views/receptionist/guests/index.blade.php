@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 bg-gray-50 min-h-screen">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Manajemen Data Tamu</h1>
        <a href="{{ route('receptionist.guests.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
            + Tambah Tamu Baru
        </a>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Nama</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Identitas</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Kontak</th>
                        <th class="text-left py-3 px-4 uppercase font-semibold text-sm">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @forelse ($guests as $guest)
                        <tr class="border-b hover:bg-gray-100">
                            <td class="py-3 px-4">{{ $guest->name }}</td>
                            <td class="py-3 px-4">{{ $guest->identity_type }} - {{ $guest->identity_number }}</td>
                            <td class="py-3 px-4">{{ $guest->phone_number ?? '-' }} / {{ $guest->email ?? '-' }}</td>
                            <td class="py-3 px-4 flex items-center">
                                <a href="{{ route('receptionist.guests.edit', $guest->id) }}" class="text-yellow-500 hover:text-yellow-700 font-semibold mr-4">Edit</a>
                                <form action="{{ route('receptionist.guests.destroy', $guest->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tamu ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-semibold">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-500">
                                Belum ada data tamu.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $guests->links() }}
        </div>
    </div>
</div>
@endsection
