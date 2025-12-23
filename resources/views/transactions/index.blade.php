@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-4 sm:py-6">
    <h1 class="text-xl sm:text-2xl font-bold mb-1 sm:mb-2">Daftar Transaksi</h1>
    <p class="text-gray-600 mb-4 sm:mb-6 text-sm sm:text-base">Kelola transaksi sampah dan penarikan saldo</p>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 text-sm" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <!-- TABS -->
    <div class="mb-4 sm:mb-6">
        <div class="border-b border-gray-200 overflow-x-auto">
            <nav class="-mb-px flex space-x-4 sm:space-x-8" aria-label="Tabs">
                <button onclick="switchTab('sampah')" id="tab-sampah"
                    class="tab-button border-b-2 border-green-500 text-green-600 whitespace-nowrap py-3 sm:py-4 px-1 font-medium text-xs sm:text-sm flex-shrink-0">
                    <i class="fas fa-trash mr-1 sm:mr-2"></i>
                    <span class="hidden sm:inline">Transaksi </span>Sampah
                    <span class="bg-green-100 text-green-800 ml-1 sm:ml-2 py-0.5 px-1.5 sm:px-2.5 rounded-full text-xs">
                        {{ $transactions->total() }}
                    </span>
                </button>
                
                <button onclick="switchTab('penarikan')" id="tab-penarikan"
                    class="tab-button border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-3 sm:py-4 px-1 font-medium text-xs sm:text-sm flex-shrink-0">
                    <i class="fas fa-money-bill-wave mr-1 sm:mr-2"></i>
                    Penarikan<span class="hidden sm:inline"> Saldo</span>
                    <span class="bg-gray-100 text-gray-800 ml-1 sm:ml-2 py-0.5 px-1.5 sm:px-2.5 rounded-full text-xs">
                        {{ $withdrawals->total() }}
                    </span>
                </button>
            </nav>
        </div>
    </div>

    <!-- TAB CONTENT: TRANSAKSI SAMPAH -->
    <div id="content-sampah" class="tab-content">
        <!-- Desktop Table -->
        <div class="hidden md:block bg-white shadow-md rounded-lg overflow-hidden">
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
                    @forelse($transactions as $trx)
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
                            <button onclick="openModal('trx-{{ $trx->id }}')" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Detail</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-6 text-center text-gray-500">Belum ada transaksi</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden space-y-3">
            @forelse($transactions as $trx)
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <p class="font-bold text-gray-800 text-sm">{{ $trx->transaction_code ?? $trx->code }}</p>
                        <p class="text-xs text-gray-500">{{ $trx->created_at->format('d/m/Y') }}</p>
                    </div>
                    @if($trx->status === 'approved')
                        <span class="bg-green-200 text-green-800 py-1 px-2 rounded-full text-xs">Approved</span>
                    @elseif($trx->status === 'rejected')
                        <span class="bg-red-200 text-red-800 py-1 px-2 rounded-full text-xs">Rejected</span>
                    @else
                        <span class="bg-yellow-200 text-yellow-800 py-1 px-2 rounded-full text-xs">Pending</span>
                    @endif
                </div>
                <div class="mb-3 space-y-1">
                    <p class="text-sm text-gray-600"><strong>User:</strong> {{ $trx->user->name }}</p>
                    <p class="text-sm text-gray-600"><strong>Kategori:</strong> {{ $trx->category->name }}</p>
                    <p class="text-sm text-gray-600"><strong>Berat:</strong> {{ number_format($trx->weight, 2) }} kg</p>
                    <p class="text-base font-bold text-gray-800">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</p>
                </div>
                <button onclick="openModal('trx-{{ $trx->id }}')" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-sm">
                    Lihat Detail
                </button>
            </div>
            @empty
            <div class="bg-white rounded-lg shadow p-6 text-center text-gray-500">
                Belum ada transaksi
            </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $transactions->links() }}
        </div>
    </div>

    <!-- TAB CONTENT: PENARIKAN SALDO -->
    <div id="content-penarikan" class="tab-content hidden">
        <!-- Desktop Table -->
        <div class="hidden md:block bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-200 text-gray-700 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">Kode</th>
                        <th class="py-3 px-6 text-left">Tanggal</th>
                        <th class="py-3 px-6 text-left">User</th>
                        <th class="py-3 px-6 text-left">Jumlah</th>
                        <th class="py-3 px-6 text-left">Status</th>
                        <th class="py-3 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @forelse($withdrawals as $wd)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left font-medium">WD-{{ $wd->id }}</td>
                        <td class="py-3 px-6">{{ $wd->created_at->format('d/m/Y') }}</td>
                        <td class="py-3 px-6">{{ $wd->user->name }}</td>
                        <td class="py-3 px-6 font-semibold">Rp {{ number_format($wd->amount, 0, ',', '.') }}</td>
                        <td class="py-3 px-6">
                            @if($wd->status === 'approved')
                                <span class="bg-green-200 text-green-800 py-1 px-3 rounded-full text-xs">Approved</span>
                            @elseif($wd->status === 'rejected')
                                <span class="bg-red-200 text-red-800 py-1 px-3 rounded-full text-xs">Rejected</span>
                            @else
                                <span class="bg-yellow-200 text-yellow-800 py-1 px-3 rounded-full text-xs">Pending</span>
                            @endif
                        </td>
                        <td class="py-3 px-6 text-center">
                            <button onclick="openModal('wd-{{ $wd->id }}')" class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">Detail</button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-6 text-center text-gray-500">Belum ada penarikan saldo</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="md:hidden space-y-3">
            @forelse($withdrawals as $wd)
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <p class="font-bold text-gray-800 text-sm">WD-{{ $wd->id }}</p>
                        <p class="text-xs text-gray-500">{{ $wd->created_at->format('d/m/Y') }}</p>
                    </div>
                    @if($wd->status === 'approved')
                        <span class="bg-green-200 text-green-800 py-1 px-2 rounded-full text-xs">Approved</span>
                    @elseif($wd->status === 'rejected')
                        <span class="bg-red-200 text-red-800 py-1 px-2 rounded-full text-xs">Rejected</span>
                    @else
                        <span class="bg-yellow-200 text-yellow-800 py-1 px-2 rounded-full text-xs">Pending</span>
                    @endif
                </div>
                <div class="mb-3">
                    <p class="text-sm text-gray-600">{{ $wd->user->name }}</p>
                    <p class="text-lg font-bold text-gray-800">Rp {{ number_format($wd->amount, 0, ',', '.') }}</p>
                </div>
                <button onclick="openModal('wd-{{ $wd->id }}')" class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-sm">
                    Lihat Detail
                </button>
            </div>
            @empty
            <div class="bg-white rounded-lg shadow p-6 text-center text-gray-500">
                Belum ada penarikan saldo
            </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $withdrawals->links() }}
        </div>
    </div>
</div>

<!-- MODAL TRANSAKSI SAMPAH -->
@foreach($transactions as $trx)
<div id="modal-trx-{{ $trx->id }}" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 z-50 p-4">
    <div class="bg-white rounded-lg w-full max-w-lg p-4 sm:p-6 relative max-h-[90vh] overflow-y-auto">
        <h2 class="text-lg sm:text-xl font-bold mb-4">Detail Transaksi {{ $trx->transaction_code ?? $trx->code }}</h2>
        <ul class="text-gray-700 space-y-2 mb-6 text-sm sm:text-base">
            <li><strong>User:</strong> {{ $trx->user->name }}</li>
            <li><strong>Email:</strong> <span class="break-all">{{ $trx->user->email }}</span></li>
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
        <div class="flex flex-col sm:flex-row gap-3 mt-6 pt-4 border-t">
            <form action="{{ route('transactions.approve', $trx->id) }}" method="POST" class="flex-1">
                @csrf
                <button type="submit" 
                        class="w-full bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition font-semibold text-sm sm:text-base"
                        onclick="return confirm('Approve transaksi ini? Saldo user akan bertambah.')">
                    ✓ Approve
                </button>
            </form>
            <form action="{{ route('transactions.reject', $trx->id) }}" method="POST" class="flex-1">
                @csrf
                <button type="submit" 
                        class="w-full bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition font-semibold text-sm sm:text-base"
                        onclick="return confirm('Reject transaksi ini?')">
                    ✗ Reject
                </button>
            </form>
        </div>
        @endif

        <button onclick="closeModal('trx-{{ $trx->id }}')" class="absolute top-2 right-2 text-gray-500 hover:text-gray-900 text-2xl font-bold leading-none">&times;</button>
    </div>
</div>
@endforeach

<!-- MODAL PENARIKAN SALDO -->
@foreach($withdrawals as $wd)
<div id="modal-wd-{{ $wd->id }}" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 z-50 p-4">
    <div class="bg-white rounded-lg w-full max-w-lg p-4 sm:p-6 relative max-h-[90vh] overflow-y-auto">
        <h2 class="text-lg sm:text-xl font-bold mb-4">Detail Penarikan WD-{{ $wd->id }}</h2>
        <ul class="text-gray-700 space-y-2 mb-6 text-sm sm:text-base">
            <li><strong>User:</strong> {{ $wd->user->name }}</li>
            <li><strong>Email:</strong> <span class="break-all">{{ $wd->user->email }}</span></li>
            <li><strong>Jumlah:</strong> Rp {{ number_format($wd->amount, 0, ',', '.') }}</li>
            <li><strong>Tanggal:</strong> {{ $wd->created_at->format('d/m/Y H:i') }}</li>
            <li><strong>Status:</strong> 
                @if($wd->status === 'approved')
                    <span class="bg-green-200 text-green-800 py-1 px-3 rounded-full text-xs">Approved</span>
                @elseif($wd->status === 'rejected')
                    <span class="bg-red-200 text-red-800 py-1 px-3 rounded-full text-xs">Rejected</span>
                @else
                    <span class="bg-yellow-200 text-yellow-800 py-1 px-3 rounded-full text-xs">Pending</span>
                @endif
            </li>
            @if($wd->notes)
            <li><strong>Catatan:</strong> {{ $wd->notes }}</li>
            @endif
        </ul>

        @if($wd->status === 'pending')
        <div class="flex flex-col sm:flex-row gap-3 mt-6 pt-4 border-t">
            <form action="{{ route('withdrawals.admin.approve', $wd->id) }}" method="POST" class="flex-1">
                @csrf
                <button type="submit" 
                        class="w-full bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition font-semibold text-sm sm:text-base"
                        onclick="return confirm('Approve penarikan ini? Saldo user akan berkurang.')">
                    ✓ Approve
                </button>
            </form>
            <form action="{{ route('withdrawals.admin.reject', $wd->id) }}" method="POST" class="flex-1">
                @csrf
                <button type="submit" 
                        class="w-full bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition font-semibold text-sm sm:text-base"
                        onclick="return confirm('Reject penarikan ini?')">
                    ✗ Reject
                </button>
            </form>
        </div>
        @endif

        <button onclick="closeModal('wd-{{ $wd->id }}')" class="absolute top-2 right-2 text-gray-500 hover:text-gray-900 text-2xl font-bold leading-none">&times;</button>
    </div>
</div>
@endforeach

<script>
// Tab switching
function switchTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('border-green-500', 'text-green-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    const activeTab = document.getElementById('tab-' + tabName);
    activeTab.classList.remove('border-transparent', 'text-gray-500');
    activeTab.classList.add('border-green-500', 'text-green-600');
}

// Modal functions
function openModal(id) {
    document.getElementById('modal-' + id).classList.remove('hidden');
    document.getElementById('modal-' + id).classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeModal(id) {
    document.getElementById('modal-' + id).classList.remove('flex');
    document.getElementById('modal-' + id).classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('bg-opacity-50')) {
        const modals = document.querySelectorAll('[id^="modal-"]');
        modals.forEach(modal => {
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        });
        document.body.style.overflow = 'auto';
    }
});
</script>
@endsection
