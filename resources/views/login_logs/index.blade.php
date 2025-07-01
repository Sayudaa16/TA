@extends('layouts.app')

@section('content')
<body class="bg-gray-100 p-6">

    <!-- Card Container -->
    <div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-lg">
        <h1 class="text-3xl font-bold mb-6 text-gray-700">Login Logs</h1>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto border-collapse border border-gray-200 rounded-lg text-left">
                <thead>
                    <tr class="bg-gray-200 text-gray-600">
                        <th class="px-6 py-3 text-sm font-semibold">User Name</th>
                        <th class="px-6 py-3 text-sm font-semibold">IP Address</th>
                        <th class="px-6 py-3 text-sm font-semibold">Login Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logs as $log)
                        <tr class="hover:bg-gray-100 border-t">
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $log->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $log->ip_address }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $log->logged_in_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Summary Table -->
        <div class="mt-8 overflow-x-auto">
            <h2 class="text-xl font-bold text-gray-700 mb-4">Login Summary</h2>
            <table class="min-w-full table-auto border-collapse border border-gray-200 rounded-lg text-left">
                <thead>
                    <tr class="bg-gray-200 text-gray-600">
                        <th class="px-6 py-3 text-sm font-semibold">User Name</th>
                        <th class="px-6 py-3 text-sm font-semibold">Total Logins</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($loginSummary as $summary)
                        <tr class="hover:bg-gray-100 border-t">
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $summary->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800">{{ $summary->total_logins }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $logs->links() }}
        </div>
    </div>

    <!-- Include Flowbite JS -->
    <script src="https://cdn.jsdelivr.net/npm/flowbite@1.5.0/dist/flowbite.bundle.min.js"></script>
</body>
@endsection
