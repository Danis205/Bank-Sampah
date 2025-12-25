@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 py-4 sm:py-6">

    <!-- HEADER -->
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('users.index') }}"
           class="text-gray-600 hover:text-gray-800">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-xl sm:text-2xl font-bold">Edit User: {{ $user->name }}</h1>
    </div>

    <!-- FORM CARD -->
    <div class="bg-white shadow rounded-lg p-4 sm:p-6">
        <form action="{{ route('users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- NAME -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $user->name) }}"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('name') border-red-500 @enderror"
                    placeholder="Masukkan nama lengkap"
                    required
                >
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- EMAIL -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email <span class="text-red-500">*</span>
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $user->email) }}"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('email') border-red-500 @enderror"
                    placeholder="contoh@email.com"
                    required
                >
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- PHONE -->
            <div class="mb-4">
                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                    Nomor Telepon
                </label>
                <input
                    type="text"
                    id="phone"
                    name="phone"
                    value="{{ old('phone', $user->phone) }}"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('phone') border-red-500 @enderror"
                    placeholder="08xxxxxxxxxx"
                >
                @error('phone')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- ADDRESS -->
            <div class="mb-4">
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                    Alamat
                </label>
                <textarea
                    id="address"
                    name="address"
                    rows="3"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('address') border-red-500 @enderror"
                    placeholder="Masukkan alamat lengkap"
                >{{ old('address', $user->address) }}</textarea>
                @error('address')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- ROLE -->
            <div class="mb-4">
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                    Role <span class="text-red-500">*</span>
                </label>
                <select
                    id="role"
                    name="role"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('role') border-red-500 @enderror"
                    required
                >
                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                @error('role')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="border-t pt-4 mb-4"></div>

            <!-- PASSWORD (OPTIONAL) -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Password Baru <span class="text-gray-500 text-xs">(Kosongkan jika tidak ingin mengubah)</span>
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('password') border-red-500 @enderror"
                    placeholder="Minimal 6 karakter"
                >
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- PASSWORD CONFIRMATION -->
            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                    Konfirmasi Password Baru
                </label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    placeholder="Ulangi password baru"
                >
            </div>

            <!-- BUTTONS -->
            <div class="flex flex-col sm:flex-row gap-3">
                <button
                    type="submit"
                    class="flex-1 bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 font-medium">
                    <i class="fas fa-save mr-1"></i> Update
                </button>
                <a
                    href="{{ route('users.index') }}"
                    class="flex-1 bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 text-center font-medium">
                    <i class="fas fa-times mr-1"></i> Batal
                </a>
            </div>
        </form>
    </div>

    <!-- INFO BOX -->
    <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-sm text-yellow-800">
        <p class="font-semibold mb-2"><i class="fas fa-exclamation-triangle mr-1"></i> Perhatian:</p>
        <ul class="list-disc list-inside space-y-1 text-xs">
            <li>Password hanya akan diubah jika Anda mengisi field password baru</li>
            <li>Mengubah email ke email yang sudah terdaftar akan ditolak</li>
            <li>Mengubah role dapat mempengaruhi akses user ke sistem</li>
            <li>Saldo dan poin user tidak dapat diubah dari form ini</li>
        </ul>
    </div>

    <!-- USER INFO BOX -->
    <div class="mt-4 bg-gray-50 border border-gray-200 rounded-lg p-4 text-sm">
        <p class="font-semibold mb-2 text-gray-700"><i class="fas fa-info-circle mr-1"></i> Info User:</p>
        <div class="grid grid-cols-2 gap-2 text-xs text-gray-600">
            <div>
                <span class="font-medium">Saldo:</span>
                <span>Rp {{ number_format($user->saldo, 0, ',', '.') }}</span>
            </div>
            <div>
                <span class="font-medium">Poin:</span>
                <span>{{ $user->total_points }} poin</span>
            </div>
            <div>
                <span class="font-medium">Terdaftar:</span>
                <span>{{ $user->created_at->format('d M Y') }}</span>
            </div>
            <div>
                <span class="font-medium">ID:</span>
                <span>#{{ $user->id }}</span>
            </div>
        </div>
    </div>
</div>
@endsection
