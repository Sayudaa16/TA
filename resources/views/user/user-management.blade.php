@extends('layouts.app')

@section('content')
<body class="bg-gray-100 font-sans antialiased">

    <div class="container mx-auto py-10">
        <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6">User Management</h2>

            <!-- Button Tambah User (ditempatkan di atas tabel) -->
            <div class="mb-6 text-right">
                <button class="px-6 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 focus:outline-none" id="addUserBtn">Tambah User</button>
            </div>

            <!-- Table (lebar diperbesar menggunakan kelas w-full) -->
            <table class="min-w-full table-auto text-left">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border-b">ID</th>
                        <th class="px-4 py-2 border-b">Name</th>
                        <th class="px-4 py-2 border-b">Email</th>
                        <th class="px-4 py-2 border-b">Role</th>
                        <th class="px-4 py-2 border-b">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td class="px-4 py-2 border-b">{{ $user->id }}</td>
                        <td class="px-4 py-2 border-b">{{ $user->name }}</td>
                        <td class="px-4 py-2 border-b">{{ $user->email }}</td>
                        <td class="px-4 py-2 border-b">{{ $user->role->name ?? 'No Role' }}</td>
                        <td class="px-4 py-2 border-b">
                            <a href="{{ route('user.edit', $user->id) }}" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Edit</a>
                            <!-- Form untuk delete user -->
                            <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal untuk menambah/edit user -->
    <div id="userModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
            <h3 class="text-xl font-semibold text-gray-800 mb-4" id="modalTitle">Tambah User</h3>

            <form action="{{ route('user.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="name" class="block text-gray-600">Name</label>
                    <input type="text" id="name" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-600">Email</label>
                    <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="password" class="block text-gray-600">Password</label>
                    <input type="password" id="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="role" class="block text-gray-600">Role</label>
                    <select id="role" name="role" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
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

    <script>
        // Script untuk modal
        const addUserBtn = document.getElementById('addUserBtn');
        const userModal = document.getElementById('userModal');
        const closeModalBtn = document.getElementById('closeModalBtn');

        addUserBtn.addEventListener('click', () => {
            userModal.classList.remove('hidden');
        });

        closeModalBtn.addEventListener('click', () => {
            userModal.classList.add('hidden');
        });
    </script>
</body>
@endsection
