@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">

    <!-- GREETING -->
    <h1 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6">
        Selamat Datang, {{ auth()->user()->name }}!
    </h1>

    <!-- STATISTIK -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-6 sm:mb-10">

        <!-- SALDO -->
        <div class="bg-white p-4 sm:p-5 rounded-xl shadow-sm border">
            <p class="text-xs sm:text-sm text-gray-500 mb-1">Saldo</p>
            <p class="text-xl sm:text-2xl font-bold mb-3 sm:mb-4">
                Rp {{ number_format($saldo,0,',','.') }}
            </p>

            <a href="{{ route('withdrawals.user.create') }}"
               class="inline-block w-full sm:w-auto text-center bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-sm sm:text-base">
                Tarik Saldo
            </a>
        </div>

        <!-- TOTAL POIN (UPGRADED) -->
        <div class="bg-gradient-to-br from-yellow-400 to-orange-500 p-4 sm:p-5 rounded-xl shadow-md text-white relative overflow-hidden">

            <!-- ICON -->
            <div class="absolute right-4 top-4 text-4xl sm:text-5xl opacity-20">
                🎁
            </div>

            <p class="text-xs sm:text-sm opacity-90 mb-1">Total Poin</p>

            <div class="flex items-end gap-2 mb-3 sm:mb-4">
                <p class="text-2xl sm:text-3xl font-extrabold">
                    {{ $points }}
                </p>
                <span class="text-xs sm:text-sm opacity-90 mb-1">poin</span>
            </div>

            <form action="{{ route('points.redeem') }}" method="POST">
                @csrf
                <button
                    type="submit"
                    class="w-full bg-white text-orange-600 font-semibold py-2 rounded-lg hover:bg-orange-50 transition disabled:opacity-50 disabled:cursor-not-allowed text-sm sm:text-base"
                    {{ $points < 10 ? 'disabled' : '' }}
                >
                    Redeem Poin
                </button>
            </form>

            @if($points < 10)
                <p class="text-xs mt-2 opacity-90">
                    Minimal 10 poin untuk redeem
                </p>
            @endif
        </div>

        <!-- TOTAL TRANSAKSI -->
        <div class="bg-white p-4 sm:p-5 rounded-xl shadow-sm border">
            <p class="text-xs sm:text-sm text-gray-500 mb-1">Total Transaksi</p>
            <p class="text-xl sm:text-2xl font-bold">
                {{ $totalTransactions }}
            </p>
        </div>

        <!-- TOTAL SAMPAH -->
        <div class="bg-white p-4 sm:p-5 rounded-xl shadow-sm border">
            <p class="text-xs sm:text-sm text-gray-500 mb-1">Total Sampah</p>
            <p class="text-xl sm:text-2xl font-bold">
                {{ number_format($totalWaste,2) }} kg
            </p>
        </div>
    </div>

    <!-- CHART -->
    <div class="bg-white p-4 sm:p-6 rounded-xl shadow-sm border mb-6 sm:mb-10">
        <h2 class="text-base sm:text-lg font-semibold mb-4">
            Grafik Penyetoran Sampah (6 Bulan Terakhir)
        </h2>

        <div class="relative h-64 sm:h-auto">
            <canvas id="wasteChart" height="120"></canvas>
        </div>
    </div>

    <!-- TRANSAKSI TERAKHIR -->
    <div class="bg-white p-4 sm:p-6 rounded-xl shadow-sm border">
        <h2 class="text-base sm:text-lg font-semibold mb-4">
            Transaksi Terakhir
        </h2>

        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="border px-4 py-2 text-sm">Tanggal</th>
                        <th class="border px-4 py-2 text-sm">Kategori</th>
                        <th class="border px-4 py-2 text-sm">Berat (kg)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions as $t)
                        <tr class="hover:bg-gray-50">
                            <td class="border px-4 py-2 text-sm">
                                {{ $t->created_at->format('d-m-Y') }}
                            </td>
                            <td class="border px-4 py-2 text-sm">
                                {{ $t->wasteCategory->name ?? '-' }}
                            </td>
                            <td class="border px-4 py-2 text-sm">
                                {{ $t->weight }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-6 text-gray-500 text-sm">
                                Belum ada transaksi
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden space-y-3">
            @forelse($recentTransactions as $t)
                <div class="border rounded-lg p-3 bg-gray-50">
                    <div class="flex justify-between items-start mb-2">
                        <span class="text-xs text-gray-500">{{ $t->created_at->format('d-m-Y') }}</span>
                    </div>
                    <p class="font-semibold text-sm text-gray-800">{{ $t->wasteCategory->name ?? '-' }}</p>
                    <p class="text-sm text-gray-600">{{ $t->weight }} kg</p>
                </div>
            @empty
                <p class="text-center py-6 text-gray-500 text-sm">
                    Belum ada transaksi
                </p>
            @endforelse
        </div>
    </div>

</div>

<!-- CHART JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('wasteChart');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            label: 'Total Sampah (kg)',
            data: @json($chartData),
            backgroundColor: '#16a34a'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>
@endsection
