@extends('layouts.app')

@section('title', 'Selamat Datang')

@section('content')
<div class="flex items-center justify-center py-12">
    <div class="bg-white shadow-xl rounded-xl p-8 w-full max-w-2xl">

        <h1 class="text-3xl font-bold text-gray-800 mb-2">Selamat Datang!</h1>
        <p class="text-gray-600 text-lg">Anda telah berhasil login ke sistem manajemen hotel.</p>

        @auth
            <div class="mt-8 border-t border-gray-200 pt-6">
                <p class="text-gray-700">Peran Anda adalah:
                    <span class="font-semibold text-blue-600 bg-blue-100 py-1 px-3 rounded-full text-sm">
                        {{-- Mengambil nama role pertama yang dimiliki user --}}
                        {{ auth()->user()->getRoleNames()->first() ?? 'Tidak Dikenali' }}
                    </span>
                </p>

                {{-- Logika untuk menampilkan tombol berdasarkan role --}}
                @if(auth()->user()->hasRole('Resepsionis'))
                    <a href="{{ route('receptionist.dashboard') }}" class="mt-6 inline-block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition-transform transform hover:scale-105 shadow-md">
                        Lanjutkan ke Dashboard Resepsionis
                    </a>
                @elseif(auth()->user()->hasRole('Owner'))
                     <a href="{{ route('owner.dashboard') }}" class="mt-6 inline-block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-lg transition-transform transform hover:scale-105 shadow-md">
                        Lanjutkan ke Dashboard Owner
                    </a>
                @endif
            </div>
        @endauth

    </div>
</div>
@endsection

