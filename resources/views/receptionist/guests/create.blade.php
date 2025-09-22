@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 bg-gray-50 min-h-screen">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Tambah Tamu Baru</h1>

    <div class="bg-white p-8 rounded-lg shadow-lg max-w-2xl mx-auto">

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Oops! Terjadi kesalahan.</strong>
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form action="{{ route('receptionist.guests.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap:</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="identity_type" class="block text-gray-700 text-sm font-bold mb-2">Jenis Identitas:</label>
                    <select name="identity_type" id="identity_type" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
                        <option value="KTP" {{ old('identity_type') == 'KTP' ? 'selected' : '' }}>KTP</option>
                        <option value="SIM" {{ old('identity_type') == 'SIM' ? 'selected' : '' }}>SIM</option>
                        <option value="Passport" {{ old('identity_type') == 'Passport' ? 'selected' : '' }}>Passport</option>
                    </select>
                </div>
                <div>
                    <label for="identity_number" class="block text-gray-700 text-sm font-bold mb-2">Nomor Identitas:</label>
                    <input type="text" name="identity_number" id="identity_number" value="{{ old('identity_number') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label for="phone_number" class="block text-gray-700 text-sm font-bold mb-2">Nomor Telepon:</label>
                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                </div>
                <div>
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Alamat Email:</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
                </div>
            </div>

            <div class="flex items-center justify-end">
                <a href="{{ route('receptionist.guests.index') }}" class="text-gray-600 hover:text-gray-800 font-bold py-2 px-4 mr-2">Batal</a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md">Simpan Tamu</button>
            </div>
        </form>
    </div>
</div>
@endsection
