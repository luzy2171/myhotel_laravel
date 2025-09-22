@extends('layouts.app')

@section('title', 'Tambah Kamar Baru')

@section('content')
<div class="container mx-auto px-4">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Form Tambah Kamar Baru</h1>

        <form action="{{ route('owner.rooms.store') }}" method="POST">
            @csrf

            <!-- Nomor Kamar -->
            <div class="mb-4">
                <label for="room_number" class="block text-gray-700 text-sm font-bold mb-2">Nomor Kamar:</label>
                <input type="text" name="room_number" id="room_number" value="{{ old('room_number') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('room_number') border-red-500 @enderror" required>
                @error('room_number')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tipe Kamar (Dropdown) -->
            <div class="mb-4">
                <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Tipe Kamar:</label>
                <select name="type" id="type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('type') border-red-500 @enderror" required>
                    <option value="">-- Pilih Tipe Kamar --</option>
                    <option value="Standard Single Bed" {{ old('type') == 'Standard Single Bed' ? 'selected' : '' }}>Standard Single Bed</option>
                    <option value="Standard Twin Bed" {{ old('type') == 'Standard Twin Bed' ? 'selected' : '' }}>Standard Twin Bed</option>
                    <option value="Deluxe Single Bed" {{ old('type') == 'Deluxe Single Bed' ? 'selected' : '' }}>Deluxe Single Bed</option>
                    <option value="Deluxe Twin Bed" {{ old('type') == 'Deluxe Twin Bed' ? 'selected' : '' }}>Deluxe Twin Bed</option>
                    <option value="Suite" {{ old('type') == 'Suite' ? 'selected' : '' }}>Suite</option>
                    <option value="Family" {{ old('type') == 'Family' ? 'selected' : '' }}>Family</option>
                </select>
                @error('type')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Harga per Malam -->
            <div class="mb-6">
                <label for="price_per_night" class="block text-gray-700 text-sm font-bold mb-2">Harga per Malam (Rp):</label>
                <input type="number" name="price_per_night" id="price_per_night" value="{{ old('price_per_night') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('price_per_night') border-red-500 @enderror" required min="0">
                @error('price_per_night')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-center justify-end">
                <a href="{{ route('owner.rooms.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">Batal</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                    Simpan Kamar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

