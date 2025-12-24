@extends('layouts.app')

@section('title', 'Edit Kategori Sampah')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 py-4 sm:py-6">

    <!-- HEADER -->
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('categories.index') }}"
           class="text-gray-600 hover:text-gray-800">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-xl sm:text-2xl font-bold">Edit Kategori Sampah</h1>
    </div>

    <!-- FORM CARD -->
    <div class="bg-white shadow rounded-lg p-4 sm:p-6">
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
                <p class="font-semibold mb-2"><i class="fas fa-exclamation-circle"></i> Terdapat kesalahan:</p>
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- CATEGORY NAME -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Kategori <span class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $category->name) }}"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('name') border-red-500 @enderror"
                    placeholder="Contoh: Plastik, Kertas, Logam"
                    required
                >
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- DESCRIPTION -->
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Deskripsi
                </label>
                <textarea
                    id="description"
                    name="description"
                    rows="3"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('description') border-red-500 @enderror"
                    placeholder="Deskripsi kategori sampah (opsional)"
                >{{ old('description', $category->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- PRICE PER KG -->
            <div class="mb-4">
                <label for="price_per_kg" class="block text-sm font-medium text-gray-700 mb-2">
                    Harga per Kg <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-2 text-gray-500">Rp</span>
                    <input
                        type="number"
                        id="price_per_kg"
                        name="price_per_kg"
                        value="{{ old('price_per_kg', $category->price_per_kg) }}"
                        class="w-full pl-12 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('price_per_kg') border-red-500 @enderror"
                        placeholder="5000"
                        min="0"
                        step="100"
                        required
                    >
                </div>
                @error('price_per_kg')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- POINTS PER KG -->
            <div class="mb-6">
                <label for="points_per_kg" class="block text-sm font-medium text-gray-700 mb-2">
                    Poin per Kg <span class="text-red-500">*</span>
                </label>
                <input
                    type="number"
                    id="points_per_kg"
                    name="points_per_kg"
                    value="{{ old('points_per_kg', $category->points_per_kg) }}"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent @error('points_per_kg') border-red-500 @enderror"
                    placeholder="5"
                    min="0"
                    required
                >
                @error('points_per_kg')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- BUTTONS -->
            <div class="flex flex-col sm:flex-row gap-3">
                <button
                    type="submit"
                    class="flex-1 bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 font-medium transition">
                    <i class="fas fa-save mr-1"></i> Simpan Perubahan
                </button>
                <a
                    href="{{ route('categories.index') }}"
                    class="flex-1 bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 text-center font-medium transition">
                    <i class="fas fa-times mr-1"></i> Batal
                </a>
            </div>
        </form>
    </div>

    <!-- INFO BOX -->
    <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4 text-sm text-blue-800">
        <p class="font-semibold mb-2"><i class="fas fa-info-circle mr-1"></i> Informasi:</p>
        <ul class="list-disc list-inside space-y-1 text-xs">
            <li>Harga per kg akan digunakan untuk menghitung total pembayaran</li>
            <li>Poin per kg akan ditambahkan ke saldo poin user</li>
            <li>Perubahan akan berlaku untuk transaksi baru</li>
        </ul>
    </div>
</div>
@endsection
