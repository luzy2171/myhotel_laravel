@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 bg-gray-50 min-h-screen">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Kamar: {{ $room->room_number }}</h1>

    <div class="bg-white p-8 rounded-lg shadow-lg max-w-2xl mx-auto">

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Oops! Terjadi kesalahan.</strong>
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('owner.rooms.update', $room->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="room_number" class="block text-gray-700 text-sm font-bold mb-2">Nomor Kamar:</label>
                <input type="text" name="room_number" id="room_number" value="{{ old('room_number', $room->room_number) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
            </div>

            <div class="mb-4">
                <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Tipe Kamar:</label>
                <select name="type" id="type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
                    <option value="Standard" {{ old('type', $room->type) == 'Standard' ? 'selected' : '' }}>Standard</option>
                    <option value="Deluxe" {{ old('type', $room->type) == 'Deluxe' ? 'selected' : '' }}>Deluxe</option>
                    <option value="Suite" {{ old('type', $room->type) == 'Suite' ? 'selected' : '' }}>Suite</option>
                    <option value="Family" {{ old('type', $room->type) == 'Family' ? 'selected' : '' }}>Family</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="price_per_night" class="block text-gray-700 text-sm font-bold mb-2">Harga per Malam (Rp):</label>
                <input type="number" name="price_per_night" id="price_per_night" value="{{ old('price_per_night', $room->price_per_night) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required min="0">
            </div>

            <div class="mb-6">
                <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status:</label>
                <select name="status" id="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
                    <option value="available" {{ old('status', $room->status) == 'available' ? 'selected' : '' }}>Available</option>
                    <option value="occupied" {{ old('status', $room->status) == 'occupied' ? 'selected' : '' }}>Occupied</option>
                    <option value="maintenance" {{ old('status', $room->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
            </div>

            <div class="flex items-center justify-end">
                <a href="{{ route('owner.rooms.index') }}" class="text-gray-600 hover:text-gray-800 font-bold py-2 px-4 mr-2">Batal</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md">Update Kamar</button>
            </div>
        </form>
    </div>
</div>
@endsection
