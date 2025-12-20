<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bank Sampah')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50">

<!-- NAVBAR -->
<nav class="bg-green-600 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            <!-- Logo -->
            <a href="{{ route('dashboard') }}" class="flex items-center">
                <i class="fas fa-recycle text-white text-2xl mr-2"></i>
                <span class="text-white text-xl font-bold">Bank Sampah</span>
            </a>

            @auth
            <div class="flex items-center gap-4">

                <a href="{{ route('dashboard') }}"
                class="text-white hover:text-green-200 px-3 py-2 text-sm font-medium">
                    <i class="fas fa-home mr-1"></i> Dashboard
                </a>

                {{-- ADMIN MENU --}}
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('transactions.index') }}"
                    class="text-white hover:text-green-200 px-3 py-2 text-sm font-medium">
                        <i class="fas fa-exchange-alt mr-1"></i> Transaksi
                    </a>

                    <a href="{{ route('categories.index') }}"
                    class="text-white hover:text-green-200 px-3 py-2 text-sm font-medium">
                        <i class="fas fa-tags mr-1"></i> Kategori
                    </a>

                    <a href="{{ route('users.index') }}"
                    class="text-white hover:text-green-200 px-3 py-2 text-sm font-medium">
                        <i class="fas fa-users mr-1"></i> Kelola User
                    </a>

                {{-- USER MENU --}}
                @else
                    <a href="{{ route('user.transactions.index') }}"
                    class="bg-white text-green-600 hover:bg-green-100 px-4 py-2 rounded-md text-sm font-semibold">
                        <i class="fas fa-trash mr-1"></i> My Transactions
                    </a>
                @endif

                    <a href="{{ route('about') }}"
                    class="text-white hover:text-green-200 px-3 py-2 text-sm font-medium">
                        <i class="fas fa-users mr-1"></i> Tentang Kami

                <!-- USER DROPDOWN (FINAL, SATU, WARAS) -->
                <div class="relative group">
                    <button
                        class="flex items-center gap-2 px-4 py-2 rounded-lg
                               hover:bg-green-700 text-white whitespace-nowrap">

                        <i class="fas fa-user-circle"></i>
                        <span class="font-medium">{{ auth()->user()->name }}</span>

                        <svg class="w-4 h-4 mt-[1px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <!-- Dropdown -->
                    <div
                        class="absolute right-0 mt-2 w-52 bg-white rounded-md shadow-lg py-1 z-50
                               opacity-0 invisible group-hover:opacity-100 group-hover:visible
                               transition-all duration-150">

                        <div class="px-4 py-2 text-sm border-b">
                            <p class="font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                        </div>

                        <a href="{{ route('dashboard') }}"
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            Dashboard
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            @else
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}"
                   class="text-white hover:text-green-200 px-4 py-2 text-sm font-medium">
                    Login
                </a>

                <a href="{{ route('register') }}"
                   class="bg-white text-green-600 hover:bg-green-50 px-4 py-2 rounded-md text-sm font-medium">
                    Daftar
                </a>
            </div>
            @endauth
        </div>
    </div>
</nav>

<!-- ALERT -->
@if(session('success'))
<div class="max-w-7xl mx-auto px-4 mt-4">
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
</div>
@endif

@if(session('error'))
<div class="max-w-7xl mx-auto px-4 mt-4">
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        {{ session('error') }}
    </div>
</div>
@endif

<!-- CONTENT -->
<main class="max-w-7xl mx-auto px-4 py-8">
    @yield('content')
</main>

<!-- FOOTER -->
<footer class="bg-white border-t mt-12">
    <div class="max-w-7xl mx-auto px-4 py-6 text-center text-gray-600">
        &copy; 2025 Bank Sampah. Kelola Sampah, Raih Manfaat.
    </div>
</footer>

</body>
</html>
