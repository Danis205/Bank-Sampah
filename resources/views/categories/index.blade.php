@extends('layouts.app')

@section('title', 'Kategori Sampah')

@section('content')
<div class="space-y-4 sm:space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sm:gap-0">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Kategori Sampah</h1>

        <a href="{{ route('categories.create') }}"
           class="bg-green-600 text-white px-4 sm:px-5 py-2 rounded-lg hover:bg-green-700 text-center text-sm sm:text-base">
            + Tambah Kategori
        </a>
    </div>

    {{-- Desktop Table --}}
    <div class="hidden md:block bg-white rounded-lg shadow overflow-hidden">
        @if($categories->count())
        <table class="w-full">
            <thead class="bg-gray-100 text-left">
                <tr>
                    <th class="p-3 text-sm">Nama</th>
                    <th class="p-3 text-sm">Harga / Kg</th>
                    <th class="p-3 text-sm">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($categories as $category)
                <tr>
                    <td class="p-3 text-sm">{{ $category->name }}</td>
                    <td class="p-3 text-sm">
                        Rp {{ number_format($category->price_per_kg,0,',','.') }}
                    </td>
                    <td class="p-3 flex gap-2">
                        <a href="{{ route('categories.edit',$category) }}"
                           class="text-blue-600 hover:underline text-sm">Edit</a>

                        <form action="{{ route('categories.destroy',$category) }}"
                              method="POST"
                              onsubmit="return confirm('Yakin hapus?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:underline text-sm">
                                Hapus
                            </button>
                        </form>
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
            Belum ada kategori.
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
                        Rp {{ number_format($category->price_per_kg,0,',','.') }}/kg
                    </p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('categories.edit',$category) }}"
                       class="flex-1 bg-blue-500 text-white px-4 py-2 rounded text-center hover:bg-blue-600 text-sm">
                        Edit
                    </a>
                    <form action="{{ route('categories.destroy',$category) }}"
                          method="POST"
                          onsubmit="return confirm('Yakin hapus?')"
                          class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button class="w-full bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 text-sm">
                            Hapus
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
            Belum ada kategori.
        </div>
        @endif
    </div>
</div>
@endsection
