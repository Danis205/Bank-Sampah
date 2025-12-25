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

        /* Smooth transitions for mobile menu */
        .mobile-menu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-in-out;
        }

        .mobile-menu.active {
            max-height: 500px;
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
                <i class="fas fa-recycle text-white text-xl sm:text-2xl mr-2"></i>
                <span class="text-white text-lg sm:text-xl font-bold">Bank Sampah</span>
            </a>

            @auth
            <!-- Desktop Menu -->
            <div class="hidden lg:flex items-center gap-4">

                <a href="{{ route('dashboard') }}"
                class="text-white hover:text-green-200 px-3 py-2 text-sm font-medium">
                    <i class="fas fa-home mr-1"></i> Dashboard
                </a>

                {{-- ADMIN MENU --}}
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('transactions.index') }}"
                    class="text-white hover:text-green-200 px-3 py-2 text-sm font-medium">
                        <i class="fas fa-exchange-alt mr-1"></i> Transactions
                    </a>

                    <a href="{{ route('categories.index') }}"
                    class="text-white hover:text-green-200 px-3 py-2 text-sm font-medium">
                        <i class="fas fa-tags mr-1"></i> Category
                    </a>

                    <a href="{{ route('users.index') }}"
                    class="text-white hover:text-green-200 px-3 py-2 text-sm font-medium">
                        <i class="fas fa-users mr-1"></i> Manage User
                    </a>

                {{-- USER MENU --}}
                @else
                    <a href="{{ route('user.transactions.index') }}"
                    class="text-white hover:text-green-200 px-3 py-2 text-sm font-medium">
                        <i class="fas fa-trash mr-1"></i> My Transactions
                    </a>
                @endif

                    <a href="{{ route('about') }}"
                    class="text-white hover:text-green-200 px-3 py-2 text-sm font-medium flex items-center">
                     <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle-fill mr-2" viewBox="0 0 16 16">
  <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16m.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2"/>
</svg><span>About Us</span>
</a>

                <!-- USER DROPDOWN (DESKTOP) -->
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

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-button" class="lg:hidden text-white p-2">
                <i class="fas fa-bars text-xl"></i>
            </button>

            @else
            <!-- Guest Links -->
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}"
                   class="text-white hover:text-green-200 px-3 sm:px-4 py-2 text-sm font-medium">
                    Login
                </a>

                <a href="{{ route('register') }}"
                   class="bg-white text-green-600 hover:bg-green-50 px-3 sm:px-4 py-2 rounded-md text-sm font-medium">
                    Daftar
                </a>
            </div>
            @endauth
        </div>

        @auth
        <!-- Mobile Menu -->
        <div id="mobile-menu" class="mobile-menu lg:hidden bg-green-700">
            <div class="px-2 pt-2 pb-3 space-y-1">

                <!-- User Info -->
                <div class="px-3 py-2 border-b border-green-600 mb-2">
                    <p class="text-white font-semibold">{{ auth()->user()->name }}</p>
                    <p class="text-green-200 text-xs">{{ auth()->user()->email }}</p>
                </div>

                <a href="{{ route('dashboard') }}"
                   class="block text-white hover:bg-green-600 px-3 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-home mr-2"></i> Dashboard
                </a>

                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('transactions.index') }}"
                       class="block text-white hover:bg-green-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-exchange-alt mr-2"></i> Transactions
                    </a>

                    <a href="{{ route('categories.index') }}"
                       class="block text-white hover:bg-green-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-tags mr-2"></i> Category
                    </a>

                    <a href="{{ route('users.index') }}"
                       class="block text-white hover:bg-green-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-users mr-2"></i> Manage User
                    </a>
                @else
                    <a href="{{ route('user.transactions.index') }}"
                       class="block text-white hover:bg-green-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-trash mr-2"></i> My Transactions
                    </a>
                @endif

                <a href="{{ route('about') }}"
                   class="block text-white hover:bg-green-600 px-3 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-info-circle mr-2"></i> About Us
                </a>

                <form method="POST" action="{{ route('logout') }}" class="mt-2">
                    @csrf
                    <button class="w-full text-left text-white hover:bg-red-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </div>
        @endauth
    </div>
</nav>

{{-- ============================================ --}}
{{-- GLOBAL ALERTS - SEMUA ALERT DI TOP --}}
{{-- Consistency: Success & Error di lokasi sama --}}
{{-- ============================================ --}}

{{-- SUCCESS ALERT --}}
@if(session('success'))
<div class="max-w-7xl mx-auto px-4 mt-4">
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded text-sm">
        {{ session('success') }}
    </div>
</div>
@endif

{{-- ERROR ALERT --}}
@if(session('error'))
<div class="max-w-7xl mx-auto px-4 mt-4">
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded text-sm">
        {{ session('error') }}
    </div>
</div>
@endif

<!-- CONTENT -->
<main class="max-w-7xl mx-auto px-4 py-4 sm:py-8">
    @yield('content')
</main>

<!-- FOOTER -->
<footer class="bg-white border-t mt-12">
    <div class="max-w-7xl mx-auto px-4 py-6 text-center text-gray-600 text-sm">
        &copy; 2025 Bank Sampah. Kelola Sampah, Raih Manfaat.
    </div>
</footer>

<!-- Mobile Menu Toggle Script -->
<script>
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('active');

            // Toggle icon
            const icon = mobileMenuButton.querySelector('i');
            if (mobileMenu.classList.contains('active')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            } else {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            }
        });
    }
</script>

@stack('scripts')

</body>
</html>
