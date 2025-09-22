<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-gray-100">
<head>
    <meta charset="utf-8"> <!-- Perbaikan: typo utf-g menjadi utf-8 -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Manajemen Hotel')</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- (DIHAPUS) Font Lama -->
    <!-- <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" /> -->

    <!-- (BARU) Font Inter yang lebih modern dan profesional -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tambahkan CDN Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- (BARU) Konfigurasi Tailwind untuk menggunakan Font Inter di seluruh aplikasi -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
</head>
<body class="h-full font-sans"> <!-- Menambahkan class font-sans untuk memastikan font diterapkan -->
    <div id="app" class="min-h-full">
        <nav class="bg-gradient-to-r from-blue-600 to-indigo-700 shadow-lg">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <a href="{{ url('/') }}" class="text-white text-xl font-bold tracking-wider">
                                PROJECT HOTEL
                            </a>
                        </div>
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline space-x-4">
                                <!-- Tautan Navigasi Dinamis -->
                                @auth
                                    @if(auth()->user()->hasRole('Owner'))
                                        <a href="{{ route('owner.dashboard') }}" class="text-gray-200 hover:bg-indigo-600 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Dashboard</a>
                                        <a href="{{ route('owner.rooms.index') }}" class="text-gray-200 hover:bg-indigo-600 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Manajemen Kamar</a>
                                        <a href="{{ route('owner.users.index') }}" class="text-gray-200 hover:bg-indigo-600 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Manajemen User</a>
                                        <a href="{{ route('owner.reports.financial') }}" class="text-gray-200 hover:bg-indigo-600 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Laporan Keuangan</a>
                                    @elseif(auth()->user()->hasRole('Resepsionis'))
                                        <a href="{{ route('receptionist.dashboard') }}" class="text-gray-200 hover:bg-indigo-600 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Dashboard</a>
                                        <a href="{{ route('receptionist.bookings.index') }}" class="text-gray-200 hover:bg-indigo-600 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Manajemen Booking</a>
                                        <a href="{{ route('receptionist.guests.index') }}" class="text-gray-200 hover:bg-indigo-600 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Manajemen Tamu</a>
                                    @endif
                                @endauth
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-4 flex items-center md:ml-6">
                             @guest
                                 @if (Route::has('login'))
                                     <a class="text-gray-200 hover:bg-indigo-600 hover:text-white rounded-md px-3 py-2 text-sm font-medium" href="{{ route('login') }}">{{ __('Login') }}</a>
                                 @endif
                             @else
                                 <div class="relative ml-3">
                                     <div>
                                         <button type="button" class="relative flex max-w-xs items-center rounded-full bg-white text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-indigo-700" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                             <span class="absolute -inset-1.5"></span>
                                             <span class="sr-only">Open user menu</span>
                                             <div class="h-8 w-8 rounded-full bg-indigo-200 flex items-center justify-center">
                                                 <span class="font-medium text-indigo-700">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                             </div>
                                         </button>
                                     </div>
                                     <div id="user-menu" class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none hidden" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                                         <a href="#" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-0">{{ Auth::user()->name }}</a>
                                         <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-2">
                                             {{ __('Logout') }}
                                         </a>
                                         <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                             @csrf
                                         </form>
                                     </div>
                                 </div>
                             @endguest
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <main class="py-10">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userMenuButton = document.getElementById('user-menu-button');
            const userMenu = document.getElementById('user-menu');

            if (userMenuButton) {
                userMenuButton.addEventListener('click', function() {
                    userMenu.classList.toggle('hidden');
                });
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('scripts')
</body>
</html>

