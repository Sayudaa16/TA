@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="w-full min-h-screen bg-gray-900 text-white p-6">
    <div class="w-full">
        <div class="mb-6">
            <h1 class="text-2xl md:text-3xl font-bold">Dashboard</h1>
            <p class="text-sm font-medium text-gray-400">Real-time security monitoring and vulnerability assessment</p>
        </div>

        <div class="w-full rounded-lg border border-gray-700 shadow-lg mb-4">
            <div id="map" class="h-[450px] w-full rounded-lg"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($shodanHosts as $host)
                <div class="bg-gray-800 rounded-xl shadow-sm border border-gray-700 overflow-hidden hover:shadow-md transition-shadow duration-200 p-6">
                    <h3 class="text-lg font-semibold mb-3">Host</h3>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-400">IP Address:</span>
                        <span class="text-white">{{ $host->ip }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-400">Country:</span>
                        <span class="text-white">{{ $host->country ?? '-' }}</span>
                    </div>
                </div>
            @endforeach
        </div>
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
