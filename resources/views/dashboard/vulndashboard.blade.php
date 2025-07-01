@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-8">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- New CVEs Today -->
            <div class="transform transition-all duration-300 hover:scale-105">
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md p-6 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">NEW CVE TODAY</p>
                            <h2 class="text-3xl font-bold text-gray-900 mt-2">{{ $newCveToday }}</h2>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-lg">
                            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Updated Today -->
            <div class="transform transition-all duration-300 hover:scale-105">
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md p-6 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">UPDATED TODAY</p>
                            <h2 class="text-3xl font-bold text-gray-900 mt-2">{{ $updatedToday }}</h2>
                        </div>
                        <div class="p-3 bg-purple-50 rounded-lg">
                            <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Weekly Known Exploited -->
            <div class="transform transition-all duration-300 hover:scale-105">
                <div class="bg-white rounded-xl shadow-sm hover:shadow-md p-6 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">WEEKLY KNOWN EXPLOITED</p>
                            <h2 class="text-3xl font-bold text-gray-900 mt-2">{{ $weeklyExploited }}</h2>
                        </div>
                        <div class="p-3 bg-red-50 rounded-lg">
                            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Vulnerability Stream -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Vulnerability Stream by Days</h2>
                <div id="vulnerabilityChart" class="h-[400px]"></div>
            </div>

            <!-- Severity Distribution -->
            <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">CVE Severity Distribution</h2>
                <div id="severityChart" class="h-[400px]"></div>
            </div>
        </div>

        <!-- Vulnerability Details Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Vulnerability Details</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CVE ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CVSS Score</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Published At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($vulnerabilities as $vulnerability)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $vulnerability->cve_id }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ Str::limit($vulnerability->description, 100) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($vulnerability->cvss_score < 4) bg-green-100 text-green-800
                                    @elseif($vulnerability->cvss_score < 7) bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ $vulnerability->cvss_score }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $vulnerability->published_at }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ $vulnerability->detail_url }}" target="_blank" 
                                   class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    View Details
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // Vulnerability Stream Chart
    const vulnerabilityData = @json($vulnerabilityData);
    const vulnerabilityLabels = Object.keys(vulnerabilityData);
    const vulnerabilityValues = Object.values(vulnerabilityData);

    var optionsBar = {
        chart: {
            type: 'area',
            height: 350,
            toolbar: {
                show: false
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800,
                animateGradually: {
                    enabled: true,
                    delay: 150
                },
                dynamicAnimation: {
                    enabled: true,
                    speed: 350
                }
            }
        },
        series: [{
            name: 'Vulnerabilities',
            data: vulnerabilityValues
        }],
        xaxis: {
            categories: vulnerabilityLabels,
            labels: {
                style: {
                    colors: '#64748b',
                    fontSize: '12px'
                }
            }
        },
        yaxis: {
            labels: {
                style: {
                    colors: '#64748b',
                    fontSize: '12px'
                }
            }
        },
        colors: ['#3b82f6'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.2,
                stops: [0, 90, 100]
            }
        },
        stroke: {
            curve: 'smooth',
            width: 2
        },
        grid: {
            borderColor: '#e2e8f0',
            strokeDashArray: 4
        },
        tooltip: {
            theme: 'light',
            x: {
                show: true
            }
        }
    };

    var chartBar = new ApexCharts(document.querySelector("#vulnerabilityChart"), optionsBar);
    chartBar.render();

    // Severity Distribution Chart
    const severityData = @json($severityDistribution);

    var optionsPie = {
        chart: {
            type: 'donut',
            height: 350,
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800,
                animateGradually: {
                    enabled: true,
                    delay: 150
                }
            }
        },
        series: Object.values(severityData),
        labels: ['Low', 'Medium', 'High'],
        colors: ['#10B981', '#F59E0B', '#EF4444'],
        plotOptions: {
            pie: {
                donut: {
                    size: '70%',
                    labels: {
                        show: true,
                        total: {
                            show: true,
                            fontSize: '16px',
                            fontFamily: 'Inter, sans-serif',
                            color: '#1F2937'
                        }
                    }
                }
            }
        },
        dataLabels: {
            enabled: true,
            formatter: function (val, opts) {
                return opts.w.config.series[opts.seriesIndex]
            }
        },
        legend: {
            position: 'bottom',
            fontSize: '14px',
            fontFamily: 'Inter, sans-serif',
            labels: {
                colors: '#64748b'
            }
        },
        tooltip: {
            theme: 'light'
        }
    };

    var chartPie = new ApexCharts(document.querySelector("#severityChart"), optionsPie);
    chartPie.render();
</script>
@endsection