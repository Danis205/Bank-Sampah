@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 sm:py-6">

    <!-- HEADER -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-6">
        <h1 class="text-xl sm:text-2xl font-bold">Daftar User</h1>

        <!-- ADD USER BUTTON -->
        <a href="{{ route('users.create') }}"
           class="w-full sm:w-auto text-center bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-sm">
            <i class="fas fa-plus mr-1"></i> Tambah User
        </a>
    </div>

    <!-- DESKTOP TABLE -->
    <div class="hidden md:block bg-white shadow rounded-lg overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3">No</th>
                    <th class="px-6 py-3">Nama</th>
                    <th class="px-6 py-3">Email</th>
                    <th class="px-6 py-3">Role</th>
                    <th class="px-6 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($users as $index => $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $users->firstItem() + $index }}</td>
                    <td class="px-6 py-4 font-medium">{{ $user->name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs
                            {{ $user->role === 'admin' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center gap-3">

                            <!-- VIEW BUTTON - Always visible -->
                            <a href="{{ route('users.show', $user) }}"
                               class="text-blue-600 hover:text-blue-800"
                               title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>

                            <!-- EDIT BUTTON - Always visible -->
                            <a href="{{ route('users.edit', $user) }}"
                               class="text-yellow-600 hover:text-yellow-800"
                               title="Edit User">
                                <i class="fas fa-edit"></i>
                            </a>

                            <!-- DELETE BUTTON - Disabled for admin role -->
                            @if($user->role === 'admin')
                                <span class="text-gray-300 cursor-not-allowed"
                                      title="Admin tidak dapat dihapus">
                                    <i class="fas fa-trash"></i>
                                </span>
                            @else
                                <form action="{{ route('users.destroy', $user) }}"
                                      method="POST"
                                      class="inline"
                                      onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}?\n\nSemua data transaksi dan withdrawal user ini akan terhapus!')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-800"
                                            title="Hapus User">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-8 text-gray-500">
                        Belum ada data user
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- MOBILE CARDS -->
    <div class="md:hidden space-y-3">
        @forelse($users as $user)
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex justify-between items-start mb-3">
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-gray-800 text-base">{{ $user->name }}</p>
                    <p class="text-sm text-gray-600 break-all">{{ $user->email }}</p>
                </div>
                <span class="ml-2 px-2 py-1 rounded text-xs flex-shrink-0
                    {{ $user->role === 'admin' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                    {{ ucfirst($user->role) }}
                </span>
            </div>

            <!-- ACTIONS -->
            <div class="flex gap-2 pt-3 border-t">
                <!-- VIEW BUTTON - Always visible -->
                <a href="{{ route('users.show', $user) }}"
                   class="flex-1 bg-blue-500 text-white px-3 py-2 rounded text-center hover:bg-blue-600 text-sm">
                    <i class="fas fa-eye mr-1"></i> Lihat
                </a>

                <!-- EDIT BUTTON - Always visible -->
                <a href="{{ route('users.edit', $user) }}"
                   class="flex-1 bg-yellow-500 text-white px-3 py-2 rounded text-center hover:bg-yellow-600 text-sm">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>

                <!-- DELETE BUTTON - Disabled for admin role -->
                @if($user->role === 'admin')
                    <div class="flex-1 bg-gray-200 text-gray-400 px-3 py-2 rounded text-center text-sm cursor-not-allowed"
                         title="Admin tidak dapat dihapus">
                        <i class="fas fa-trash mr-1"></i> Hapus
                    </div>
                @else
                    <form action="{{ route('users.destroy', $user) }}"
                          method="POST"
                          class="flex-1"
                          onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}?\n\nSemua data transaksi dan withdrawal user ini akan terhapus!')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full bg-red-500 text-white px-3 py-2 rounded hover:bg-red-600 text-sm">
                            <i class="fas fa-trash mr-1"></i> Hapus
                        </button>
                    </form>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow p-6 text-center text-gray-500">
            Belum ada data user
        </div>
        @endforelse
    </div>

    <!-- PAGINATION -->
    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>
@endsection
