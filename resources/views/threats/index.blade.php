@extends('layouts.app')

@section('content')
<body class="bg-gray-50">
    <!-- Main Content -->
    <div class="container mx-auto px-4 mt-4">
        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 fade-in mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover-scale">
                <h3 class="text-sm font-medium text-slate-500">Total Threat Groups</h3>
                <p class="text-2xl font-bold text-slate-800 mt-2">{{ $allThreatGroups->count() }}</p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover-scale">
                <h3 class="text-sm font-medium text-slate-500">Countries Affected</h3>
                <p class="text-2xl font-bold text-slate-800 mt-2">
                    {{ $allThreatGroups->pluck('country')->flatten()->unique()->count() }}
                </p>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 hover-scale">
                <h3 class="text-sm font-medium text-slate-500">Last Updated</h3>
                <p class="text-2xl font-bold text-slate-800 mt-2">
                    {{ $allThreatGroups->first() ? \Carbon\Carbon::parse($allThreatGroups->first()['last-card-change'])->format('M d, Y') : 'N/A' }}
                </p>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8 mb-8">
            <!-- Country Distribution Chart -->
            <div class="bg-white p-6 shadow rounded-lg">
                <h2 class="text-lg font-semibold mb-4">Threat Groups Targeting Indonesia</h2>
                <div id="countryChart"></div>
            </div>

            <!-- Actor Count by Name Giver Chart -->
            <div class="bg-white p-6 shadow rounded-lg">
                <h2 class="text-lg font-semibold mb-4">Actor Count by Name Giver</h2>
                <div id="nameGiverChart"></div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="overflow-x-auto relative shadow-md sm:rounded-lg mb-8">
	<!-- Search Form -->
<div class="p-6 bg-white rounded-xl shadow-sm border border-slate-200 mb-4">
    <form method="GET" action="{{ route('threats.index') }}">
        <div class="flex items-center space-x-2">
            <input type="text" name="search" placeholder="Search Threat Actor or Country"
                value="{{ request('search') }}"
                class="w-full px-4 py-2 border rounded-lg focus:ring focus:ring-indigo-200 text-sm" />
            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm">Search</button>
        </div>
    </form>
</div>


            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3">Name</th>
                        <th scope="col" class="px-6 py-3">Country</th>
                        <th scope="col" class="px-6 py-3">Description</th>
                        <th scope="col" class="px-6 py-3">Last Update</th>
                    </tr>
                </thead>
                <tbody id="threatTableBody">
                    @foreach($paginatedThreatGroups as $group)
                    <tr class="{{ $loop->index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} border-b">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $group['actor'] ?? 'Unknown' }}</td>
                        <td class="px-6 py-4">{{ implode(', ', $group['country'] ?? ['Unknown']) }}</td>
                        <td class="px-6 py-4">{{ $group['description'] ?? 'No description available' }}</td>
                        <td class="px-6 py-4">{{ $group['last-card-change'] ?? 'N/A' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="px-6 py-3">
                {{ $paginatedThreatGroups->links() }}
            </div>
        </div>
    </div>

    <script>
        // Ambil data dari Blade ke JavaScript
        const allThreatGroups = @json($allThreatGroups);

        // Olah data untuk chart
        const countries = {};
        const nameGivers = {};

        allThreatGroups.forEach(group => {
            // Hitung berdasarkan negara
            group.country.forEach(country => {
                countries[country] = (countries[country] || 0) + 1;
            });

            // Hitung berdasarkan pemberi nama
            group.names.forEach(name => {
                let giver = name["name-giver"];

                if (!giver || giver.trim() === "?" || giver.trim() === "") {
                    giver = "Unknown";
                }

                nameGivers[giver] = (nameGivers[giver] || 0) + 1;
            });
        });

        // Data untuk Chart Country Distribution menggunakan ApexCharts
        const countryChartOptions = {
            chart: {
                type: 'pie',
                height: 350,
                events: {
                    dataPointSelection: function(event, chartContext, config) {
                        if (config.dataPointIndex !== undefined) {
                            const selectedCountry = config.w.config.labels[config.dataPointIndex];
                            filterTableByCountry(selectedCountry);
                        }
                    }
                }
            },
            series: Object.values(countries),
            labels: Object.keys(countries),
            title: {
                text: 'Threat Actors by Country',
                align: 'center'
            }
        };

        const countryChart = new ApexCharts(document.querySelector("#countryChart"), countryChartOptions);
        countryChart.render();

        // Fungsi untuk memfilter tabel berdasarkan negara yang diklik
        function filterTableByCountry(country) {
            const tableBody = document.getElementById('threatTableBody');
            tableBody.innerHTML = ''; // Kosongkan tabel

            const filteredData = allThreatGroups.filter(group => group.country.includes(country));

            filteredData.forEach(group => {
                const row = `
                    <tr class="border-b ${filteredData.indexOf(group) % 2 === 0 ? 'bg-white' : 'bg-gray-50'}">
                        <td class="px-6 py-4 font-medium text-gray-900">${group.actor ?? 'Unknown'}</td>
                        <td class="px-6 py-4">${group.country.join(', ')}</td>
                        <td class="px-6 py-4">${group.description ?? 'No description available'}</td>
                        <td class="px-6 py-4">${group['last-card-change'] ?? 'N/A'}</td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });
        }

        // Data untuk Chart Name Giver Distribution menggunakan ApexCharts
        const nameGiverChartOptions = {
            chart: {
                type: 'line',
                height: 350
            },
            series: [{
                name: 'Actor Count by Name Giver',
                data: Object.values(nameGivers)
            }],
            xaxis: {
                categories: Object.keys(nameGivers)
            },
            title: {
                text: 'Actor Count by Name Giver',
                align: 'center'
            },
            stroke: {
                curve: 'smooth'
            },
            markers: {
                size: 5
            },
            dataLabels: {
                enabled: false
            },
            grid: {
                show: true
            }
        };

        const nameGiverChart = new ApexCharts(document.querySelector("#nameGiverChart"), nameGiverChartOptions);
        nameGiverChart.render();
    </script>

</body>
@endsection
