@extends('layouts.app')

@section('content')
<div class="container mx-auto py-10">
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Edit User</h2>

        <form action="{{ route('user.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT') <!-- Menambahkan method PUT untuk form update -->
            <div class="mb-4">
                <label for="name" class="block text-gray-600">Name</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-600">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-600">Password (kosongkan jika tidak ingin diubah)</label>
                <input type="password" id="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label for="role" class="block text-gray-600">Role</label>
                <select id="role" name="role" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end">
                <button type="button" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400" id="closeModalBtn">Batal</button>
                <button type="submit" class="ml-4 px-6 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
