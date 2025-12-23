@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-4 sm:py-6">
    <h1 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6">Daftar User</h1>

    <!-- Desktop Table -->
    <div class="hidden md:block overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3">No</th>
                    <th class="px-6 py-3">Nama</th>
                    <th class="px-6 py-3">Email</th>
                    <th class="px-6 py-3">Role</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach ($users as $index => $user)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4">{{ $index + 1 }}</td>
                    <td class="px-6 py-4 font-medium">{{ $user->name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs 
                            {{ $user->role === 'admin' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards -->
    <div class="md:hidden space-y-3">
        @foreach ($users as $index => $user)
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex justify-between items-start mb-2">
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-gray-800 text-base">{{ $user->name }}</p>
                    <p class="text-sm text-gray-600 break-all">{{ $user->email }}</p>
                </div>
                <span class="ml-2 px-2 py-1 rounded text-xs flex-shrink-0
                    {{ $user->role === 'admin' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                    {{ ucfirst($user->role) }}
                </span>
            </div>
            <p class="text-xs text-gray-500">User #{{ $index + 1 }}</p>
        </div>
        @endforeach
    </div>
</div>
@endsection
