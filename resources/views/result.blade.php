@extends('layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <!-- Judul -->
    <h5 class="text-lg font-semibold text-white mb-4">Details for IP: {{ $host->ip }}</h5>

    <!-- Detail IP -->
    <div class="bg-gray-900 rounded-lg shadow-lg overflow-hidden mb-6">
        <div class="p-6">
            <table class="w-full text-sm text-left text-gray-300">
                <tbody>
                    <tr class="border-b border-gray-700">
                        <th class="py-2 px-4 font-medium">IP Address</th>
                        <td class="py-2 px-4">{{ $host->ip }}</td>
                    </tr>
                    <tr class="border-b border-gray-700">
                        <th class="py-2 px-4 font-medium">Hostnames</th>
                        <td class="py-2 px-4">
                            @php
                                $hostnames = json_decode($host->hostnames);
                                echo is_array($hostnames) ? implode(', ', $hostnames) : 'N/A';
                            @endphp
                        </td>
                    </tr>
                    <tr class="border-b border-gray-700">
                        <th class="py-2 px-4 font-medium">Domains</th>
                        <td class="py-2 px-4">
                            @php
                                $domains = json_decode($host->domains);
                                echo is_array($domains) ? implode(', ', $domains) : 'N/A';
                            @endphp
                        </td>
                    </tr>
                    <tr class="border-b border-gray-700">
                        <th class="py-2 px-4 font-medium">Country</th>
                        <td class="py-2 px-4">{{ $host->country }}</td>
                    </tr>
                    <tr class="border-b border-gray-700">
                        <th class="py-2 px-4 font-medium">City</th>
                        <td class="py-2 px-4">{{ $host->city }}</td>
                    </tr>
                    <tr class="border-b border-gray-700">
                        <th class="py-2 px-4 font-medium">ISP</th>
                        <td class="py-2 px-4">{{ $host->isp }}</td>
                    </tr>
                    <tr class="border-b border-gray-700">
                        <th class="py-2 px-4 font-medium">ASN</th>
                        <td class="py-2 px-4">{{ $host->asn }}</td>
                    </tr>
                    <tr>
                        <th class="py-2 px-4 font-medium">Open Ports</th>
                        <td class="py-2 px-4">
                            @php
                                $ports = json_decode($host->ports);
                                echo is_array($ports) ? implode(', ', $ports) : 'N/A';
                            @endphp
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="flex gap-4 mt-4">
                <a href="{{ route('dashboard.indexd') }}" class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-600">Back to Dashboard</a>
                <a href="{{ route('monitor.exportPdf', $host->id) }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Export to PDF</a>
            </div>
        </div>
    </div>

    <!-- Map -->
    @if(!empty($host->latitude) && !empty($host->longitude))
    <div class="bg-gray-900 text-white rounded-lg shadow-lg p-6 mb-6">
        <h5 class="text-lg font-bold mb-4">Peta Lokasi IP</h5>
        <div id="map-{{ $host->id }}" class="h-96"></div>
        <script>
            var map = L.map('map-{{ $host->id }}').setView([{{ $host->latitude }}, {{ $host->longitude }}], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(map);
            L.marker([{{ $host->latitude }}, {{ $host->longitude }}]).addTo(map)
                .bindPopup('<b>IP Address:</b> {{ $host->ip }}<br><b>ISP:</b> {{ $host->isp ?? "N/A" }}')
                .openPopup();
        </script>
    </div>
    @endif

    <!-- Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Vulnerabilities -->
        <div class="bg-gray-900 rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gray-800 px-6 py-4 text-white">
                <h5 class="text-lg font-semibold">Vulnerabilities (CVE)</h5>
            </div>
            <div class="p-6 text-gray-300">
                @if ($host->vulns)
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-700 text-gray-300">
                        <tr>
                            <th class="py-2 px-4">CVE ID</th>
                            <th class="py-2 px-4">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach (json_decode($host->vulns) as $vuln)
                        <tr class="border-b border-gray-700">
                            <td class="py-2 px-4">{{ $vuln }}</td>
                            <td class="py-2 px-4">{{ json_decode($host->cve_details)->{$vuln} ?? 'No details available' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-gray-400"><em>No vulnerabilities detected.</em></p>
                @endif
            </div>
        </div>

        <!-- Service Banners -->
        <div class="bg-gray-900 rounded-lg shadow-lg overflow-hidden">
            <div class="bg-gray-800 px-6 py-4 text-white">
                <h5 class="text-lg font-semibold">Service Banners</h5>
            </div>
            <div class="p-6 text-gray-300">
                @php
                    $serviceBanners = json_decode($host->service_banners);
                @endphp
                @if ($serviceBanners && is_array($serviceBanners))
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-700 text-gray-300">
                        <tr>
                            <th class="py-2 px-4">Port</th>
                            <th class="py-2 px-4">Service Banner</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($serviceBanners as $service)
                        <tr class="border-b border-gray-700">
                            <td class="py-2 px-4">{{ $service->port }}</td>
                            <td class="py-2 px-4">{{ $service->banner }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-gray-400"><em>No service banners available.</em></p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
