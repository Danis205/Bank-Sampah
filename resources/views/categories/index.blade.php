@extends('layouts.app')

@section('title', 'Kategori Sampah')

@section('content')
<div class="space-y-4 sm:space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sm:gap-0">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Kategori Sampah</h1>

        <a href="{{ route('categories.create') }}"
           class="bg-green-600 text-white px-4 sm:px-5 py-2 rounded-lg hover:bg-green-700 transition text-center text-sm sm:text-base font-medium">
            <i class="fas fa-plus mr-1"></i> Tambah Kategori
        </a>
    </div>

    {{-- Desktop Table --}}
    <div class="hidden md:block bg-white rounded-lg shadow overflow-hidden">
        @if($categories->count())
        <table class="w-full">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="px-4 py-3 text-sm font-semibold text-gray-700">Nama</th>
                    <th class="px-4 py-3 text-sm font-semibold text-gray-700">Harga / Kg</th>
                    <th class="px-4 py-3 text-sm font-semibold text-gray-700 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($categories as $category)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-4 text-sm font-medium text-gray-800">{{ $category->name }}</td>
                    <td class="px-4 py-4 text-sm text-gray-600">
                        Rp {{ number_format($category->price_per_kg, 0, ',', '.') }}
                    </td>
                    <td class="px-4 py-4">
                        <div class="flex items-center justify-center gap-3">
                            <!-- EDIT BUTTON -->
                            <a href="{{ route('categories.edit', $category) }}"
                               class="text-blue-600 hover:text-blue-800 transition"
                               title="Edit Kategori">
                                <i class="fas fa-edit"></i>
                            </a>

                            <!-- DELETE BUTTON -->
                            <form action="{{ route('categories.destroy', $category) }}"
                                  method="POST"
                                  class="inline"
                                  onsubmit="return confirm('Yakin ingin menghapus kategori {{ $category->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-red-600 hover:text-red-800 transition"
                                        title="Hapus Kategori">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="p-4">
            {{ $categories->links() }}
        </div>
        @else
        <div class="p-10 text-center text-gray-500 text-sm">
            <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
            <p>Belum ada kategori sampah.</p>
        </div>
        @endif
    </div>

    {{-- Mobile Cards --}}
    <div class="md:hidden space-y-3">
        @if($categories->count())
            @foreach($categories as $category)
            <div class="bg-white rounded-lg shadow p-4">
                <div class="mb-3">
                    <h3 class="font-bold text-gray-800 text-base">{{ $category->name }}</h3>
                    <p class="text-lg font-semibold text-green-600 mt-1">
                        Rp {{ number_format($category->price_per_kg, 0, ',', '.') }}/kg
                    </p>
                </div>
                <div class="flex gap-2">
                    <!-- EDIT BUTTON -->
                    <a href="{{ route('categories.edit', $category) }}"
                       class="flex-1 bg-blue-500 text-white px-4 py-2 rounded-lg text-center hover:bg-blue-600 transition text-sm font-medium">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </a>

                    <!-- DELETE BUTTON -->
                    <form action="{{ route('categories.destroy', $category) }}"
                          method="POST"
                          onsubmit="return confirm('Yakin ingin menghapus kategori {{ $category->name }}?')"
                          class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition text-sm font-medium">
                            <i class="fas fa-trash mr-1"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
            @endforeach

            <div class="mt-4">
                {{ $categories->links() }}
            </div>
        @else
        <div class="bg-white rounded-lg shadow p-6 text-center text-gray-500 text-sm">
            <i class="fas fa-inbox text-4xl mb-3 text-gray-300"></i>
            <p>Belum ada kategori sampah.</p>
        </div>
        @endif
    </div>
</div>
@endsection
