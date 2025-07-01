@extends('layouts.app')

@section('content')
<div class="w-full min-h-screen bg-gray-900 text-white p-6">
    <div class="w-full mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-bold">Dashboard</h1>
            <p class="text-sm font-medium text-gray-400">Real-time security monitoring and vulnerability assessment</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div class="rounded-lg bg-white shadow p-4 dark:bg-gray-800">
                <div class="text-gray-500 dark:text-gray-400">Total Ports</div>
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalPorts }}</div>
            </div>
            <div class="rounded-lg bg-white shadow p-4 dark:bg-gray-800">
                <div class="text-gray-500 dark:text-gray-400">Total Vulnerabilities Scan</div>
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalVulns }}</div>
            </div>
            <div class="rounded-lg bg-white shadow p-4 dark:bg-gray-800">
                <div class="text-gray-500 dark:text-gray-400">Total Hosts</div>
                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalHosts }}</div>
            </div>
        </div>




        <div class="w-full rounded-lg border border-gray-700 shadow-lg mb-4">
            <div id="map" class="h-[450px] w-full rounded-lg"></div>
        </div>
    </div>

        <!-- Alert Messages -->
        @if (session('success'))
            <div class="mb-6 rounded-lg bg-emerald-800/30 px-4 py-3 shadow-sm border border-emerald-600 flex items-center">
                <svg class="h-5 w-5 text-emerald-400 mr-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <p class="text-sm text-emerald-300 font-medium">{{ session('success') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 rounded-lg bg-red-800/30 px-4 py-3 shadow-sm border border-red-600">
                <div class="flex items-center mb-2">
                    <svg class="h-5 w-5 text-red-400 mr-3" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <h3 class="text-sm font-medium text-red-300">Please correct the following errors:</h3>
                </div>
                <ul class="list-disc list-inside pl-3 text-sm text-red-200">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Scan Form Section -->
        <div class="mb-8">
            @if(auth()->user()->role_id == 1)
                <button
                    class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150"
                    id="toggleFormButton"
                    title="Add new scan">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </button>
            @endif

            <div id="scanForm" class="hidden mt-4">
                <div class="bg-gray-800 rounded-xl shadow-sm border border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-white mb-4">New Scan</h2>
                    <form action="{{ route('scan') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="ip" class="block text-sm font-medium text-gray-300 mb-1">IP Address</label>
                                <input
                                    type="text"
                                    name="ip"
                                    id="ip"
                                    class="block w-full rounded-md border-gray-700 bg-gray-900 text-white placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                    placeholder="Enter IP address to scan"
                                    required>
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-300 mb-1">Notification Email</label>
                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    class="block w-full rounded-md border-gray-700 bg-gray-900 text-white placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                    placeholder="Enter email for notifications"
                                    required>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button
                                type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Start Scan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Scan Results Section -->
        @if ($hosts->isEmpty())
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <h3 class="mt-4 text-sm font-medium text-gray-400">No scans yet</h3>
                <p class="mt-1 text-sm text-gray-500">Start by scanning an IP address</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($hosts as $host)
                    <div class="bg-gray-800 rounded-xl shadow-sm border border-gray-700 overflow-hidden hover:shadow-md transition-shadow duration-200">
                        <div class="relative">
                            <form action="{{ route('hosts.destroy', $host->id) }}" method="POST" class="absolute top-4 right-4">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="p-1 rounded-full hover:bg-gray-700 text-gray-400 hover:text-red-500 transition-colors duration-150"
                                    onclick="return confirm('Are you sure you want to delete this scan?');">
                                    <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </form>

                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-white mb-4">Scan Results</h3>
                                <dl class="space-y-3">
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-400">IP Address</dt>
                                        <dd class="text-sm text-white">{{ $host->ip }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-400">Country</dt>
                                        <dd class="text-sm text-white">{{ $host->country }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-sm font-medium text-gray-400">City</dt>
                                        <dd class="text-sm text-white">{{ $host->city }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <div class="px-6 pb-6 space-y-3">
                                <a href="{{ route('result', ['id' => $host->id]) }}"
                                    class="block w-full text-center px-4 py-2 rounded-md text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                    View Details
                                </a>
                                <a href="{{ route('monitor.exportPdf', $host->id) }}"
                                    class="block w-full text-center px-4 py-2 rounded-md text-sm font-medium text-gray-300 bg-gray-700 hover:bg-gray-600">
                                    Export PDF
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    const locations = @json($locations);
    const map = L.map('map').setView([0, 0], 2);

    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://carto.com/">CARTO</a>'
    }).addTo(map);

    locations.forEach(location => {
        if (location.latitude && location.longitude) {
            L.marker([location.latitude, location.longitude])
                .addTo(map)
                .bindPopup(`
                    <div class="text-sm">
                        <div class="font-bold mb-1">IP Address: ${location.ip}</div>
                        <div class="text-gray-400">Country: ${location.country_name || '-'}</div>
                    </div>
                `);
        }
    });
</script>
<script>
    document.getElementById('toggleFormButton').addEventListener('click', function() {
        const form = document.getElementById('scanForm');
        form.classList.toggle('hidden');
    });
</script>
@endsection
