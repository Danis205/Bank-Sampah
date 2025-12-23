@extends('layouts.app')

@section('title', 'Penarikan Saldo')

@section('content')
<div class="max-w-5xl mx-auto">

    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold">Penarikan Saldo</h1>
            <p class="text-gray-500 text-xs sm:text-sm">
                Tarik saldo hasil setoran sampah kamu
            </p>
        </div>

        <!-- BUTTON TARIK SALDO -->
        <a href="{{ route('withdrawals.user.create') }}"
           class="bg-green-600 hover:bg-green-700 text-white px-4 sm:px-5 py-2 rounded-lg font-medium shadow text-center text-sm sm:text-base whitespace-nowrap">
            <i class="fas fa-wallet mr-2"></i> Tarik Saldo
        </a>
    </div>

    <!-- INFO SALDO -->
    <div class="bg-white rounded-lg shadow p-4 sm:p-5 mb-6 flex items-center justify-between">
        <div>
            <p class="text-xs sm:text-sm text-gray-500">Saldo Kamu</p>
            <p class="text-xl sm:text-2xl font-bold text-green-600">
                Rp {{ number_format(auth()->user()->saldo, 0, ',', '.') }}
            </p>
        </div>
        <i class="fas fa-money-bill-wave text-2xl sm:text-3xl text-green-500"></i>
    </div>

    <!-- TABEL - Desktop View -->
    <div class="hidden md:block bg-white shadow rounded-lg overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-100 text-gray-600">
                <tr>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Jumlah</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($withdrawals as $withdrawal)
                <tr class="border-t">
                    <td class="px-4 py-3">
                        {{ $withdrawal->created_at->format('d M Y') }}
                    </td>

                    <td class="px-4 py-3 font-semibold">
                        Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}
                    </td>

                    <td class="px-4 py-3">
                        @if($withdrawal->status === 'pending')
                            <span class="px-3 py-1 text-xs rounded bg-yellow-100 text-yellow-700">
                                Pending
                            </span>
                        @elseif($withdrawal->status === 'approved')
                            <span class="px-3 py-1 text-xs rounded bg-green-100 text-green-700">
                                Approved
                            </span>
                        @else
                            <span class="px-3 py-1 text-xs rounded bg-red-100 text-red-700">
                                Rejected
                            </span>
                        @endif
                    </td>

                    <td class="px-4 py-3 text-center">
                        <a href="{{ route('withdrawals.user.show', $withdrawal->id) }}"
                           class="text-green-600 hover:underline text-sm font-medium">
                            Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                        Belum ada penarikan saldo.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- CARD LIST - Mobile View -->
    <div class="md:hidden space-y-3">
        @forelse($withdrawals as $withdrawal)
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <p class="text-xs text-gray-500">{{ $withdrawal->created_at->format('d M Y') }}</p>
                    <p class="text-lg font-bold text-gray-800 mt-1">
                        Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}
                    </p>
                </div>
                <div>
                    @if($withdrawal->status === 'pending')
                        <span class="px-3 py-1 text-xs rounded bg-yellow-100 text-yellow-700">
                            Pending
                        </span>
                    @elseif($withdrawal->status === 'approved')
                        <span class="px-3 py-1 text-xs rounded bg-green-100 text-green-700">
                            Approved
                        </span>
                    @else
                        <span class="px-3 py-1 text-xs rounded bg-red-100 text-red-700">
                            Rejected
                        </span>
                    @endif
                </div>
            </div>
            <a href="{{ route('withdrawals.user.show', $withdrawal->id) }}"
               class="block w-full text-center bg-green-600 hover:bg-green-700 text-white py-2 rounded text-sm font-medium">
                Lihat Detail
            </a>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow p-6 text-center text-gray-500">
            Belum ada penarikan saldo.
        </div>
        @endforelse
    </div>

    <!-- PAGINATION -->
    <div class="mt-4">
        {{ $withdrawals->links() }}
    </div>

</div>
@endsection
