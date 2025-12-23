@extends('layouts.app')

@section('title', 'Dashboard Admin - Bank Sampah')

@section('content')
<div class="space-y-4 sm:space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-4 sm:p-6">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Dashboard Admin</h1>
        <p class="text-gray-600 mt-1 text-sm sm:text-base">Selamat datang, {{ auth()->user()->name }}</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6">
        <!-- Total Users -->
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-600 font-medium">Total Nasabah</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-800 mt-2">{{ $totalUsers }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-2 sm:p-3">
                    <i class="fas fa-users text-blue-600 text-xl sm:text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Transactions -->
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-600 font-medium">Total Transaksi</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-800 mt-2">{{ $totalTransactions }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-2 sm:p-3">
                    <i class="fas fa-exchange-alt text-green-600 text-xl sm:text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Waste -->
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm text-gray-600 font-medium">Total Sampah</p>
                    <p class="text-2xl sm:text-3xl font-bold text-gray-800 mt-2">{{ number_format($totalWaste, 2) }}</p>
                    <p class="text-xs text-gray-500 mt-1">Kilogram</p>
                </div>
                <div class="bg-yellow-100 rounded-full p-2 sm:p-3">
                    <i class="fas fa-trash-alt text-yellow-600 text-xl sm:text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <div class="flex items-center justify-between">
                <div class="min-w-0">
                    <p class="text-xs sm:text-sm text-gray-600 font-medium">Total Pendapatan</p>
                    <p class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800 mt-2 truncate">
                        Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                    </p>
                </div>
                <div class="bg-purple-100 rounded-full p-2 sm:p-3 flex-shrink-0">
                    <i class="fas fa-money-bill-wave text-purple-600 text-xl sm:text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        <!-- Monthly Waste Chart -->
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <h3 class="text-sm sm:text-lg font-semibold text-gray-800 mb-4">
                Grafik Sampah per Bulan (6 Bulan Terakhir)
            </h3>
            <div class="relative h-64 sm:h-auto">
                <canvas id="monthlyWasteChart"></canvas>
            </div>
        </div>

        <!-- Waste by Category Chart -->
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <h3 class="text-sm sm:text-lg font-semibold text-gray-800 mb-4">Sampah per Kategori</h3>
            <div class="relative h-64 sm:h-auto">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Pending Approvals -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        <!-- Pending Transactions -->
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <h3 class="text-sm sm:text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-clock text-yellow-500 mr-2"></i>
                Transaksi Menunggu Persetujuan
            </h3>
            @if($pendingTransactions->count() > 0)
            <div class="space-y-3">
                @foreach($pendingTransactions as $transaction)
                <div class="border border-gray-200 rounded-lg p-3 sm:p-4">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2">
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-800 text-sm sm:text-base truncate">
                                {{ $transaction->user->name }}
                            </p>
                            <p class="text-xs sm:text-sm text-gray-600">
                                {{ $transaction->wasteCategory->name }} - {{ $transaction->weight }} kg
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $transaction->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                        <div class="text-left sm:text-right flex-shrink-0">
                            <p class="font-semibold text-green-600 text-sm sm:text-base">
                                Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                            </p>
                            <a href="{{ route('transactions.show', $transaction) }}" 
                               class="text-xs sm:text-sm text-blue-600 hover:text-blue-800">
                                Detail →
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <a href="{{ route('transactions.index') }}" 
               class="block text-center mt-4 text-blue-600 hover:text-blue-800 font-medium text-sm sm:text-base">
                Lihat Semua →
            </a>
            @else
            <p class="text-gray-500 text-center py-4 text-xs sm:text-sm">
                Tidak ada transaksi menunggu persetujuan
            </p>
            @endif
        </div>

        <!-- Pending Withdrawals -->
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <h3 class="text-sm sm:text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-clock text-yellow-500 mr-2"></i>
                Penarikan Menunggu Persetujuan
            </h3>
            @if($pendingWithdrawals->count() > 0)
            <div class="space-y-3">
                @foreach($pendingWithdrawals as $withdrawal)
                <div class="border border-gray-200 rounded-lg p-3 sm:p-4">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2">
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-800 text-sm sm:text-base truncate">
                                {{ $withdrawal->user->name }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $withdrawal->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                        <div class="text-left sm:text-right flex-shrink-0">
                            <p class="font-semibold text-red-600 text-sm sm:text-base">
                                Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}
                            </p>
                            <a href="{{ route('withdrawals.user.show', $withdrawal) }}" 
                               class="text-xs sm:text-sm text-blue-600 hover:text-blue-800">
                                Detail →
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <a href="{{ route('withdrawals.admin.index') }}" 
               class="block text-center mt-4 text-blue-600 hover:text-blue-800 font-medium text-sm sm:text-base">
                Lihat Semua →
            </a>
            @else
            <p class="text-gray-500 text-center py-4 text-xs sm:text-sm">
                Tidak ada penarikan menunggu persetujuan
            </p>
            @endif
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
        <a href="{{ route('reports.admin') }}" 
           class="bg-blue-600 text-white px-4 sm:px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-200 flex items-center justify-center text-sm sm:text-base">
            <i class="fas fa-file-pdf mr-2"></i>
            Download Laporan PDF
        </a>
        <a href="{{ route('categories.index') }}" 
           class="bg-green-600 text-white px-4 sm:px-6 py-3 rounded-lg hover:bg-green-700 transition duration-200 flex items-center justify-center text-sm sm:text-base">
            <i class="fas fa-tags mr-2"></i>
            Kelola Kategori Sampah
        </a>
    </div>
</div>

@push('scripts')
<script>
    // Monthly Waste Chart
    const monthlyCtx = document.getElementById('monthlyWasteChart').getContext('2d');
    const monthlyData = @json($monthlyWaste);
    
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: monthlyData.map(item => item.month),
            datasets: [{
                label: 'Berat Sampah (kg)',
                data: monthlyData.map(item => item.total_weight),
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Category Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const categoryData = @json($wasteByCategory);
    
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: categoryData.map(item => item.name),
            datasets: [{
                data: categoryData.map(item => item.total_weight),
                backgroundColor: [
                    'rgb(59, 130, 246)',
                    'rgb(34, 197, 94)',
                    'rgb(251, 191, 36)',
                    'rgb(239, 68, 68)',
                    'rgb(168, 85, 247)',
                    'rgb(236, 72, 153)'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
</script>
@endpush
@endsection
