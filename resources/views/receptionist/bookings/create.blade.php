@extends('layouts.app')

@section('title', 'Buat Booking Baru')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Formulir Booking Baru</h1>

        <form action="{{ route('receptionist.bookings.store') }}" method="POST">
            @csrf

            <!-- Pilih Tamu -->
            <div class="mb-4">
                <label for="guest_id" class="block text-gray-700 text-sm font-bold mb-2">Pilih Tamu:</label>
                <select name="guest_id" id="guest_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <option value="">-- Pilih Nama Tamu --</option>
                    @foreach ($guests as $guest)
                        <option value="{{ $guest->id }}" {{ old('guest_id', request()->guest_id) == $guest->id ? 'selected' : '' }}>
                            {{ $guest->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Pilih Kamar -->
            <div class="mb-4">
                <label for="room_id" class="block text-gray-700 text-sm font-bold mb-2">Pilih Kamar (Hanya yang Tersedia):</label>
                <select name="room_id" id="room_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    @if($availableRooms->isEmpty())
                        <option value="" disabled>-- Tidak ada kamar yang tersedia --</option>
                    @else
                        <option value="">-- Pilih Nomor Kamar --</option>
                        @foreach ($availableRooms as $room)
                            {{-- Menyimpan harga kamar di data attribute untuk digunakan oleh JavaScript --}}
                            <option value="{{ $room->id }}" data-price="{{ $room->price_per_night }}" {{ old('room_id', request()->room_id) == $room->id ? 'selected' : '' }}>
                                Kamar {{ $room->room_number }} ({{ $room->type }}) - Rp{{ number_format($room->price_per_night, 0, ',', '.') }}/malam
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <!-- Tanggal Check-in & Check-out -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="check_in_date" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Check-in:</label>
                    <input type="date" name="check_in_date" id="check_in_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('check_in_date') }}" required>
                </div>
                <div>
                    <label for="check_out_date" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Check-out:</label>
                    <input type="date" name="check_out_date" id="check_out_date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('check_out_date') }}" required>
                </div>
            </div>

            <!-- Total Harga (Dihitung Otomatis) -->
            <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 text-blue-800 p-4 rounded-md">
                <h3 class="font-bold">Estimasi Total Harga:</h3>
                <p id="total-price" class="text-2xl font-semibold">Rp 0</p>
                <p id="price-details" class="text-sm"></p>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-center justify-end">
                <a href="{{ route('receptionist.bookings.index') }}" class="text-gray-600 hover:text-gray-800 font-bold py-2 px-4 mr-4">Batal</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                    Simpan Booking
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const roomSelect = document.getElementById('room_id');
        const checkInInput = document.getElementById('check_in_date');
        const checkOutInput = document.getElementById('check_out_date');
        const totalPriceEl = document.getElementById('total-price');
        const priceDetailsEl = document.getElementById('price-details');

        function calculateTotal() {
            const selectedOption = roomSelect.options[roomSelect.selectedIndex];
            const pricePerNight = parseFloat(selectedOption.getAttribute('data-price'));
            const checkInDate = new Date(checkInInput.value);
            const checkOutDate = new Date(checkOutInput.value);

            if (isNaN(pricePerNight) || !checkInInput.value || !checkOutInput.value || checkOutDate <= checkInDate) {
                totalPriceEl.textContent = 'Rp 0';
                priceDetailsEl.textContent = '';
                return;
            }

            const timeDiff = checkOutDate.getTime() - checkInDate.getTime();
            const nights = Math.ceil(timeDiff / (1000 * 3600 * 24));

            if (nights > 0) {
                const total = nights * pricePerNight;
                totalPriceEl.textContent = 'Rp' + new Intl.NumberFormat('id-ID').format(total);
                priceDetailsEl.textContent = nights + ' malam x Rp' + new Intl.NumberFormat('id-ID').format(pricePerNight);
            } else {
                totalPriceEl.textContent = 'Rp 0';
                priceDetailsEl.textContent = '';
            }
        }

        roomSelect.addEventListener('change', calculateTotal);
        checkInInput.addEventListener('change', calculateTotal);
        checkOutInput.addEventListener('change', calculateTotal);

        // Hitung saat halaman dimuat jika ada data
        calculateTotal();
    });
</script>
@endpush
@endsection

