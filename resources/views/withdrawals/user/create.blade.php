@extends('layouts.app')

@section('title', 'Tarik Saldo')

@section('content')
<div class="max-w-xl mx-auto bg-white shadow rounded-lg p-4 sm:p-6">

    <h1 class="text-xl sm:text-2xl font-bold text-gray-800 mb-2">
        Tarik Saldo
    </h1>

    <p class="text-xs sm:text-sm text-gray-500 mb-4 sm:mb-6">
        Ajukan penarikan saldo hasil setor sampah
    </p>

    {{-- ============================================ --}}
    {{-- ALERTS DIHAPUS DARI SINI --}}
    {{-- Semua alert sekarang di app.blade.php (TOP) --}}
    {{-- ============================================ --}}

    <form method="POST" action="{{ route('withdrawals.user.store') }}">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Saldo Anda
            </label>
            <input type="text"
                   class="w-full border rounded px-3 py-2 bg-gray-100 text-sm sm:text-base"
                   value="Rp {{ number_format(auth()->user()->saldo, 0, ',', '.') }}"
                   disabled>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Jumlah Penarikan
            </label>
            <input type="number"
                   name="amount"
                   min="1000"
                   required
                   class="w-full border rounded px-3 py-2 focus:outline-none focus:ring focus:border-green-500 text-sm sm:text-base"
                   placeholder="Minimal Rp 1.000">
            @error('amount')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex flex-col sm:flex-row justify-end gap-2 mt-4 sm:mt-6">
            <a href="{{ route('dashboard') }}"
               class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 text-center text-sm sm:text-base">
                Batal
            </a>

            <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 text-sm sm:text-base">
                Ajukan Penarikan
            </button>
        </div>
    </form>
</div>
@endsection
