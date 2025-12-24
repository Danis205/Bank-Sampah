@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-4 sm:py-6">
    
    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('users.index') }}" 
               class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1 class="text-xl sm:text-2xl font-bold">Detail User</h1>
        </div>
        
        @if($user->id !== auth()->id())
        <div class="flex gap-2 w-full sm:w-auto">
            <a href="{{ route('users.edit', $user) }}" 
               class="flex-1 sm:flex-none bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600 text-sm text-center">
                <i class="fas fa-edit mr-1"></i> Edit
            </a>
            <form action="{{ route('users.destroy', $user) }}" 
                  method="POST" 
                  class="flex-1 sm:flex-none"
                  onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}?\n\nSemua data transaksi dan withdrawal user ini akan terhapus!')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="w-full bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 text-sm">
                    <i class="fas fa-trash mr-1"></i> Hapus
                </button>
            </form>
        </div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- USER INFO CARD -->
        <div class="lg:col-span-1">
            <div class="bg-white shadow rounded-lg p-6">
                
                <!-- AVATAR -->
                <div class="flex justify-center mb-4">
                    <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-green-600 text-4xl"></i>
                    </div>
                </div>

                <!-- NAME & ROLE -->
                <div class="text-center mb-4">
                    <h2 class="text-xl font-bold text-gray-800">{{ $user->name }}</h2>
                    <span class="inline-block mt-2 px-3 py-1 rounded-full text-sm
                        {{ $user->role === 'admin' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </div>

                <div class="border-t pt-4 space-y-3 text-sm">
                    <!-- EMAIL -->
                    <div class="flex items-start gap-3">
                        <i class="fas fa-envelope text-gray-400 mt-1 w-4"></i>
                        <div class="flex-1 min-w-0">
                            <p class="text-gray-500 text-xs">Email</p>
                            <p class="text-gray-800 break-all">{{ $user->email }}</p>
                        </div>
                    </div>

                    <!-- PHONE -->
                    @if($user->phone)
                    <div class="flex items-start gap-3">
                        <i class="fas fa-phone text-gray-400 mt-1 w-4"></i>
                        <div class="flex-1 min-w-0">
                            <p class="text-gray-500 text-xs">Telepon</p>
                            <p class="text-gray-800">{{ $user->phone }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- ADDRESS -->
                    @if($user->address)
                    <div class="flex items-start gap-3">
                        <i class="fas fa-map-marker-alt text-gray-400 mt-1 w-4"></i>
                        <div class="flex-1 min-w-0">
                            <p class="text-gray-500 text-xs">Alamat</p>
                            <p class="text-gray-800">{{ $user->address }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- MEMBER SINCE -->
                    <div class="flex items-start gap-3">
                        <i class="fas fa-calendar text-gray-400 mt-1 w-4"></i>
                        <div class="flex-1 min-w-0">
                            <p class="text-gray-500 text-xs">Terdaftar Sejak</p>
                            <p class="text-gray-800">{{ $user->created_at->format('d F Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- STATISTICS & TRANSACTIONS -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- STATISTICS CARDS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                
                <!-- SALDO -->
                <div class="bg-white shadow rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs text-gray-500">Saldo</p>
                        <i class="fas fa-wallet text-green-500"></i>
                    </div>
                    <p class="text-lg font-bold text-gray-800">
                        Rp {{ number_format($user->saldo, 0, ',', '.') }}
                    </p>
                </div>

                <!-- POINTS -->
                <div class="bg-white shadow rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs text-gray-500">Total Poin</p>
                        <i class="fas fa-star text-yellow-500"></i>
                    </div>
                    <p class="text-lg font-bold text-gray-800">
                        {{ $user->total_points }} poin
                    </p>
                </div>

                <!-- TOTAL TRANSACTIONS -->
                <div class="bg-white shadow rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs text-gray-500">Total Transaksi</p>
                        <i class="fas fa-exchange-alt text-blue-500"></i>
                    </div>
                    <p class="text-lg font-bold text-gray-800">
                        {{ $totalTransactions }}
                    </p>
                </div>

                <!-- TOTAL WASTE -->
                <div class="bg-white shadow rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <p class="text-xs text-gray-500">Total Sampah</p>
                        <i class="fas fa-trash text-red-500"></i>
                    </div>
                    <p class="text-lg font-bold text-gray-800">
                        {{ number_format($totalWaste, 2) }} kg
                    </p>
                </div>
            </div>

            <!-- RECENT TRANSACTIONS -->
            <div class="bg-white shadow rounded-lg">
                <div class="p-4 sm:p-6 border-b">
                    <h3 class="text-lg font-bold text-gray-800">
                        Transaksi Terakhir (10 Terbaru)
                    </h3>
                </div>

                <!-- DESKTOP TABLE -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 text-gray-700 text-xs uppercase">
                            <tr>
                                <th class="px-6 py-3 text-left">Tanggal</th>
                                <th class="px-6 py-3 text-left">Kategori</th>
                                <th class="px-6 py-3 text-right">Berat</th>
                                <th class="px-6 py-3 text-right">Total</th>
                                <th class="px-6 py-3 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y">
                            @forelse($transactions as $t)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-3 text-gray-800">
                                    {{ $t->created_at->format('d M Y') }}
                                </td>
                                <td class="px-6 py-3 text-gray-800">
                                    {{ $t->wasteCategory->name ?? '-' }}
                                </td>
                                <td class="px-6 py-3 text-right text-gray-800">
                                    {{ $t->weight }} kg
                                </td>
                                <td class="px-6 py-3 text-right text-gray-800">
                                    Rp {{ number_format($t->total_price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-3 text-center">
                                    @if($t->status === 'approved')
                                        <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">
                                            Disetujui
                                        </span>
                                    @elseif($t->status === 'pending')
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs">
                                            Pending
                                        </span>
                                    @else
                                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs">
                                            Ditolak
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-8 text-gray-500">
                                    Belum ada transaksi
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- MOBILE CARDS -->
                <div class="md:hidden p-4 space-y-3">
                    @forelse($transactions as $t)
                    <div class="border rounded-lg p-3 bg-gray-50">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <p class="font-semibold text-gray-800 text-sm">
                                    {{ $t->wasteCategory->name ?? '-' }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $t->created_at->format('d M Y') }}
                                </p>
                            </div>
                            @if($t->status === 'approved')
                                <span class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">
                                    Disetujui
                                </span>
                            @elseif($t->status === 'pending')
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs">
                                    Pending
                                </span>
                            @else
                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs">
                                    Ditolak
                                </span>
                            @endif
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ $t->weight }} kg</span>
                            <span class="font-semibold text-gray-800">
                                Rp {{ number_format($t->total_price, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <p class="text-center py-6 text-gray-500 text-sm">
                        Belum ada transaksi
                    </p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
