@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-4 sm:py-6">
    <h1 class="text-xl sm:text-2xl font-bold mb-1">Withdrawal Requests</h1>
    <p class="text-gray-600 mb-4 sm:mb-6 text-sm sm:text-base">Permintaan penarikan saldo dari user</p>

    <!-- Desktop Table View -->
    <div class="hidden md:block overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full table-auto">
            <thead>
                <tr class="bg-gray-200 text-gray-700 uppercase text-sm">
                    <th class="py-3 px-6 text-left">Kode</th>
                    <th class="py-3 px-6">Tanggal</th>
                    <th class="py-3 px-6">User</th>
                    <th class="py-3 px-6">Jumlah</th>
                    <th class="py-3 px-6">Status</th>
                    <th class="py-3 px-6 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="text-gray-600 text-sm">
                @forelse($withdrawals as $wd)
                <tr class="border-b hover:bg-gray-100">
                    <td class="py-3 px-6 font-medium">WD-{{ $wd->id }}</td>
                    <td class="py-3 px-6">{{ $wd->created_at->format('d/m/Y') }}</td>
                    <td class="py-3 px-6">{{ $wd->user->name }}</td>
                    <td class="py-3 px-6 font-semibold">
                        Rp {{ number_format($wd->amount, 0, ',', '.') }}
                    </td>

                    <td class="py-3 px-6">
                        @if($wd->status === 'approved')
                            <span class="bg-green-200 text-green-800 px-3 py-1 rounded-full text-xs">
                                Approved
                            </span>
                        @elseif($wd->status === 'rejected')
                            <span class="bg-red-200 text-red-800 px-3 py-1 rounded-full text-xs">
                                Rejected
                            </span>
                        @else
                            <span class="bg-yellow-200 text-yellow-800 px-3 py-1 rounded-full text-xs">
                                Pending
                            </span>
                        @endif
                    </td>

                    <td class="py-3 px-6 text-center">
                        <button onclick="openModal('{{ $wd->id }}')" 
                                class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600">
                           Detail
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-6 text-center text-gray-500">Belum ada permintaan penarikan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile Card View -->
    <div class="md:hidden space-y-3">
        @forelse($withdrawals as $wd)
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex justify-between items-start mb-3">
                <div>
                    <p class="font-bold text-gray-800">WD-{{ $wd->id }}</p>
                    <p class="text-xs text-gray-500">{{ $wd->created_at->format('d/m/Y') }}</p>
                </div>
                @if($wd->status === 'approved')
                    <span class="bg-green-200 text-green-800 px-2 py-1 rounded-full text-xs">
                        Approved
                    </span>
                @elseif($wd->status === 'rejected')
                    <span class="bg-red-200 text-red-800 px-2 py-1 rounded-full text-xs">
                        Rejected
                    </span>
                @else
                    <span class="bg-yellow-200 text-yellow-800 px-2 py-1 rounded-full text-xs">
                        Pending
                    </span>
                @endif
            </div>
            
            <div class="mb-3">
                <p class="text-sm text-gray-600">{{ $wd->user->name }}</p>
                <p class="text-lg font-bold text-gray-800">
                    Rp {{ number_format($wd->amount, 0, ',', '.') }}
                </p>
            </div>

            <button onclick="openModal('{{ $wd->id }}')" 
                    class="w-full bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 text-sm">
                Lihat Detail
            </button>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow p-6 text-center text-gray-500">
            Belum ada permintaan penarikan
        </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $withdrawals->links() }}
    </div>
</div>

<!-- MODALS -->
@foreach($withdrawals as $wd)
<div id="modal-{{ $wd->id }}" class="fixed inset-0 hidden items-center justify-center bg-black bg-opacity-50 z-50 p-4">
    <div class="bg-white rounded-lg w-full max-w-lg p-4 sm:p-6 relative max-h-[90vh] overflow-y-auto">
        <h2 class="text-lg sm:text-xl font-bold mb-4">Detail Penarikan WD-{{ $wd->id }}</h2>
        
        <ul class="text-gray-700 space-y-2 mb-6 text-sm sm:text-base">
            <li><strong>User:</strong> {{ $wd->user->name }}</li>
            <li><strong>Email:</strong> <span class="break-all">{{ $wd->user->email }}</span></li>
            <li><strong>Jumlah:</strong> Rp {{ number_format($wd->amount, 0, ',', '.') }}</li>
            <li><strong>Tanggal Request:</strong> {{ $wd->created_at->format('d/m/Y H:i') }}</li>
            <li><strong>Status:</strong> 
                @if($wd->status === 'approved')
                    <span class="bg-green-200 text-green-800 px-3 py-1 rounded-full text-xs">Approved</span>
                @elseif($wd->status === 'rejected')
                    <span class="bg-red-200 text-red-800 px-3 py-1 rounded-full text-xs">Rejected</span>
                @else
                    <span class="bg-yellow-200 text-yellow-800 px-3 py-1 rounded-full text-xs">Pending</span>
                @endif
            </li>
            @if($wd->notes)
            <li><strong>Catatan:</strong> {{ $wd->notes }}</li>
            @endif
        </ul>

        @if($wd->status === 'pending')
        <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t">
            <form action="{{ route('withdrawals.admin.approve', $wd->id) }}" method="POST" class="flex-1">
                @csrf
                <button type="submit"
                        class="w-full bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 font-semibold text-sm sm:text-base"
                        onclick="return confirm('Approve penarikan ini? Saldo user akan berkurang Rp {{ number_format($wd->amount, 0, ',', '.') }}')">
                    ✓ Approve
                </button>
            </form>

            <form action="{{ route('withdrawals.admin.reject', $wd->id) }}" method="POST" class="flex-1">
                @csrf
                <button type="submit"
                        class="w-full bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 font-semibold text-sm sm:text-base"
                        onclick="return confirm('Reject penarikan ini?')">
                    ✗ Reject
                </button>
            </form>
        </div>
        @endif

        <button onclick="closeModal('{{ $wd->id }}')" 
                class="absolute top-2 right-2 text-gray-500 hover:text-gray-900 text-2xl font-bold">&times;</button>
    </div>
</div>
@endforeach

<script>
function openModal(id) {
    document.getElementById('modal-' + id).classList.remove('hidden');
    document.getElementById('modal-' + id).classList.add('flex');
    document.body.style.overflow = 'hidden'; // Prevent background scrolling
}

function closeModal(id) {
    document.getElementById('modal-' + id).classList.remove('flex');
    document.getElementById('modal-' + id).classList.add('hidden');
    document.body.style.overflow = 'auto'; // Restore scrolling
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
