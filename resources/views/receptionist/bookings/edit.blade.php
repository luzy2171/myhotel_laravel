@extends('layouts.app')

@section('title', 'Edit Booking')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Booking</h1>

    @include('layouts.partials.alert')

    <div class="bg-white p-8 rounded-lg shadow-md">
        <form action="{{ route('receptionist.bookings.update', $booking->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Pilih Tamu -->
            <div class="mb-4">
                <label for="guest_id" class="block text-gray-700 text-sm font-bold mb-2">Pilih Tamu:</label>
                <select name="guest_id" id="guest_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    @foreach ($guests as $guest)
                        <option value="{{ $guest->id }}" {{ $booking->guest_id == $guest->id ? 'selected' : '' }}>
                            {{ $guest->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Pilih Kamar -->
            <div class="mb-4">
                <label for="room_id" class="block text-gray-700 text-sm font-bold mb-2">Pilih Kamar:</label>
                <select name="room_id" id="room_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                     @foreach ($availableRooms as $room)
                        <option value="{{ $room->id }}" {{ $booking->room_id == $room->id ? 'selected' : '' }}>
                            Kamar {{ $room->room_number }} ({{ $room->type }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Tanggal Check-in -->
                <div class="mb-4">
                    <label for="check_in_date" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Check-in:</label>
                    <input type="date" name="check_in_date" id="check_in_date" value="{{ old('check_in_date', $booking->check_in_date) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>

                <!-- Tanggal Check-out -->
                <div class="mb-4">
                    <label for="check_out_date" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Check-out:</label>
                    <input type="date" name="check_out_date" id="check_out_date" value="{{ old('check_out_date', $booking->check_out_date) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
            </div>

            <!-- Status Booking -->
            <div class="mb-6">
                <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status Booking:</label>
                <select name="status" id="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="checked_in" {{ $booking->status == 'checked_in' ? 'selected' : '' }}>Checked In</option>
                    <option value="checked_out" {{ $booking->status == 'checked_out' ? 'selected' : '' }}>Checked Out</option>
                    <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>

            <!-- Tombol -->
            <div class="flex items-center justify-end">
                <a href="{{ route('receptionist.bookings.index') }}" class="text-gray-600 hover:text-gray-800 mr-4">Batal</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
