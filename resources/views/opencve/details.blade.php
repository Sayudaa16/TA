@extends('layouts.app')

@section('content')
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <!-- Header Section -->
        <div class="bg-white shadow rounded p-4 mb-6">
            <h1 class="text-2xl font-bold mb-2">{{ $cve['cve_id'] }}</h1>
            <p class="text-gray-600">{{ $cve['description'] }}</p>
        </div>

        <!-- Metrics Section -->
        <div class="bg-white shadow rounded p-4 mb-6">
            <h2 class="text-xl font-semibold mb-4">Metrics</h2>
            <div class="grid grid-cols-3 gap-4">
                <div class="p-4 border rounded bg-gray-50">
                    <p class="text-sm font-medium">CVSS v3.1</p>
                    <p class="text-xl text-red-600 font-bold">
                        {{ $cve['metrics']['cvssV3_1'] }}
                    </p>
                </div>
                <div class="p-4 border rounded bg-gray-50">
                    <p class="text-sm font-medium">KEV</p>
                    <p class="text-xl font-bold">
                        {{ $cve['metrics']['kev'] }}
                    </p>
                </div>
                <div class="p-4 border rounded bg-gray-50">
                    <p class="text-sm font-medium">SSVC</p>
                    <p class="text-xl text-green-600 font-bold">
                        {{ $cve['metrics']['ssvc'] }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Affected Vendors & Products -->
        <div class="bg-white shadow rounded p-4 mb-6">
            <h2 class="text-xl font-semibold mb-4">Affected Vendors & Products</h2>
            <div class="text-gray-500">
                @if (!empty($cve['vendors']))
                    <ul class="list-disc list-inside">
                        @foreach ($cve['vendors'] as $vendor)
                            <li>{{ $vendor }}</li>
                        @endforeach
                    </ul>
                @else
                    No data available.
                @endif
            </div>
        </div>

        <!-- References Section -->
        <div class="bg-white shadow rounded p-4 mb-6">
            <h2 class="text-xl font-semibold mb-4">References</h2>
            <ul class="list-disc list-inside text-blue-600">
                @if (!empty($cve['references']))
                    @foreach ($cve['references'] as $reference)
                        <li><a href="{{ $reference }}" target="_blank" class="hover:underline">{{ $reference }}</a></li>
                    @endforeach
                @else
                    <li>No references available.</li>
                @endif
            </ul>

        </div>

        <!-- History Section -->
        <div class="bg-white shadow rounded p-4">
            <h2 class="text-xl font-semibold mb-4">History</h2>
            <div class="overflow-auto">
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border border-gray-300 p-2">Date</th>
                            <th class="border border-gray-300 p-2">Type</th>
                            <th class="border border-gray-300 p-2">Values Removed</th>
                            <th class="border border-gray-300 p-2">Values Added</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (!empty($cve['history']))
                            @foreach ($cve['history'] as $history)
                                <tr>
                                    <td class="border border-gray-300 p-2">{{ $history['date'] }}</td>
                                    <td class="border border-gray-300 p-2">{{ $history['type'] }}</td>
                                    <td class="border border-gray-300 p-2">{{ $history['values_removed'] }}</td>
                                    <td class="border border-gray-300 p-2">{{ $history['values_added'] }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="border border-gray-300 p-2 text-center">No history available.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
@endsection
