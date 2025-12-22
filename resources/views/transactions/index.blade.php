@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-2">Daftar Transaksi</h1>
    <p class="text-gray-600 mb-6">Kelola seluruh transaksi nasabah</p>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full table-auto">
            <thead>
                <tr class="bg-gray-200 text-gray-700 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Kode</th>
                    <th class="py-3 px-6 text-left">Tanggal</th>
                    <th class="py-3 px-6 text-left">User</th>
                    <th class="py-3 px-6 text-left">Kategori</th>
                    <th class="py-3 px-6 text-left">Berat (kg)</th>
                    <th class="py-3 px-6 text-left">Total</th>
                    <th class="py-3 px-6 text-left">Status</th>
                    <th class="py-3 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach($transactions as $trx)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left font-medium">{{ $trx->transaction_code ?? $trx->code }}</td>
                    <td class="py-3 px-6">{{ $trx->created_at->format('d/m/Y') }}</td>
                    <td class="py-3 px-6">{{ $trx->user->name }}</td>
                    <td class="py-3 px-6">{{ $trx->category->name }}</td>
                    <td class="py-3 px-6">{{ number_format($trx->weight, 2) }}</td>
                    <td class="py-3 px-6">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</td>
                    <td class="py-3 px-6">
                        @if($trx->status === 'approved')
                            <span class="bg-green-200 text-green-800 py-1 px-3 rounded-full text-xs">Approved</span>
                        @elseif($trx->status === 'rejected')
                            <span class="bg-red-200 text-red-800 py-1 px-3 rounded-full text-xs">Rejected</span>
                        @else
                            <span class="bg-yellow-200 text-yellow-800 py-1 px-3 rounded-full text-xs">Pending</span>
                        @endif
                    </td>
                    <td class="py-3 px-6 text-center">
                        <button onclick="openModal('{{ $trx->id }}')" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Detail</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $transactions->links() }}
    </div>
</div>

<!-- Modal -->
@foreach($transactions as $trx)
<div id="modal-{{ $trx->id }}" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white rounded-lg w-11/12 md:w-1/2 p-6 relative">
        <h2 class="text-xl font-bold mb-4">Detail Transaksi {{ $trx->transaction_code ?? $trx->code }}</h2>
        <ul class="text-gray-700 space-y-2 mb-6">
            <li><strong>User:</strong> {{ $trx->user->name }}</li>
            <li><strong>Email:</strong> {{ $trx->user->email }}</li>
            <li><strong>Kategori:</strong> {{ $trx->category->name }}</li>
            <li><strong>Berat:</strong> {{ number_format($trx->weight, 2) }} kg</li>
            <li><strong>Harga per kg:</strong> Rp {{ number_format($trx->price_per_kg ?? 0, 0, ',', '.') }}</li>
            <li><strong>Total:</strong> Rp {{ number_format($trx->total_price, 0, ',', '.') }}</li>
            <li><strong>Tanggal:</strong> {{ $trx->created_at->format('d/m/Y H:i') }}</li>
            <li><strong>Status:</strong> 
                @if($trx->status === 'approved')
                    <span class="bg-green-200 text-green-800 py-1 px-3 rounded-full text-xs">Approved</span>
                @elseif($trx->status === 'rejected')
                    <span class="bg-red-200 text-red-800 py-1 px-3 rounded-full text-xs">Rejected</span>
                @else
                    <span class="bg-yellow-200 text-yellow-800 py-1 px-3 rounded-full text-xs">Pending</span>
                @endif
            </li>
            @if($trx->notes)
            <li><strong>Catatan:</strong> {{ $trx->notes }}</li>
            @endif
        </ul>

        @if($trx->status === 'pending')
        <div class="flex gap-3 mt-6 pt-4 border-t">
            <form action="{{ route('transactions.approve', $trx->id) }}" method="POST" class="flex-1">
                @csrf
                <button type="submit" 
                        class="w-full bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition font-semibold"
                        onclick="return confirm('Apakah Anda yakin ingin menyetujui transaksi ini?')">
                    ✓ Approve
                </button>
            </form>
            <form action="{{ route('transactions.reject', $trx->id) }}" method="POST" class="flex-1">
                @csrf
                <button type="submit" 
                        class="w-full bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition font-semibold"
                        onclick="return confirm('Apakah Anda yakin ingin menolak transaksi ini?')">
                    ✗ Reject
                </button>
            </form>
        </div>
        @endif

        <button onclick="closeModal('{{ $trx->id }}')" class="absolute top-2 right-2 text-gray-500 hover:text-gray-900 text-2xl font-bold leading-none">&times;</button>
    </div>
</div>
@endforeach

<script>
function openModal(id) {
    document.getElementById('modal-' + id).classList.remove('hidden');
    document.getElementById('modal-' + id).classList.add('flex');
}

function closeModal(id) {
    document.getElementById('modal-' + id).classList.remove('flex');
    document.getElementById('modal-' + id).classList.add('hidden');
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('bg-opacity-50')) {
        const modals = document.querySelectorAll('[id^="modal-"]');
        modals.forEach(modal => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        });
    }
});
</script>
@endsection
