@extends('layouts.app')

@section('content')
<style>
    .card {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        border-radius: 0.5rem;
    }
    .card:hover {
        box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
    }
    .severity-low {
        color: #28a745; /* Green */
    }
    .severity-medium {
        color: #ffc107; /* Yellow */
    }
    .severity-high {
        color: #dc3545; /* Red */
    }
    .severity-unknown {
        color: #6c757d; /* Gray */
    }
</style>

<div class="container mx-auto p-4">

    <!-- Menampilkan pesan error jika ada -->
    @if(session('error'))
        <div class="bg-red-500 text-white p-4 rounded mb-4 text-center">
            {{ session('error') }}
        </div>
    @endif

    <!-- Menampilkan Latest CVE -->
    @if(count($latestVulnerabilities) > 0)
        <h2 class="text-2xl font-bold text-center mb-4 text-blue-600">Latest CVE</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($latestVulnerabilities as $vuln)
                <div class="card bg-white p-6 shadow-lg hover:shadow-xl">
                    <!-- CVSS Score and Badge -->
                    <div class="text-center mb-4">
                        @php
                            $score = $vuln->cvss_score ?? '0.0'; // Jika cvss_score tidak ada, set default menjadi '0.0'
                        @endphp
                        @if($score === 'Not Available' || $score === '0.0')
                            <h1 class="text-5xl font-bold severity-unknown">{{ $score }}</h1>
                        @else
                            @if($score >= 7.0)
                                <h1 class="text-5xl font-bold severity-high">{{ $score }}</h1>
                            @elseif($score >= 4.0)
                                <h1 class="text-5xl font-bold severity-medium">{{ $score }}</h1>
                            @else
                                <h1 class="text-5xl font-bold severity-low">{{ $score }}</h1>
                            @endif
                        @endif
                        <span class="text-xs text-gray-500">CVSS Score</span>
                    </div>

                    <!-- CVE Details -->
                    <h2 class="text-2xl font-semibold text-blue-700 mb-2">{{ $vuln->cve_id }}</h2>
                    <p class="text-gray-600 mt-2 mb-4 text-sm">
                        {{ Str::limit($vuln->description, 120, '...') }}
                    </p>
                    <p class="text-sm text-gray-500">
                        Published:
                        <span class="font-semibold">
                            {{ \Carbon\Carbon::parse($vuln->published_at)->format('d M Y') }}
                        </span>
                    </p>
                    <a href="{{ $vuln->detail_url }}"
                        class="text-blue-500 hover:text-blue-700 mt-4 inline-block text-sm font-semibold">
                         More Info
                     </a>
                    {{-- <a href="{{ route('cve.detail', ['cveId' => $vuln->cve_id]) }}"
                        class="text-blue-500 hover:text-blue-700 mt-4 inline-block text-sm font-semibold">
                         More Info
                     </a> --}}

                </div>
            @endforeach
        </div>
    @else
        <p class="text-center text-gray-500">No latest vulnerabilities found.</p>
    @endif

    <!-- Menampilkan Other CVE -->
    @if(count($otherVulnerabilities) > 0)
        <h2 class="text-2xl font-bold text-center mb-5 mt-5 text-blue-600">Other CVE</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($otherVulnerabilities as $vuln)
                <div class="card bg-white p-6 shadow-lg hover:shadow-xl">
                    <!-- CVSS Score and Badge -->
                    <div class="text-center mb-4">
                        @php
                            $score = $vuln->cvss_score ?? '0.0'; // Jika cvss_score tidak ada, set default menjadi '0.0'
                        @endphp
                        @if($score === 'Not Available' || $score === '0.0')
                            <h1 class="text-5xl font-bold severity-unknown">{{ $score }}</h1>
                        @else
                            @if($score >= 7.0)
                                <h1 class="text-5xl font-bold severity-high">{{ $score }}</h1>
                            @elseif($score >= 4.0)
                                <h1 class="text-5xl font-bold severity-medium">{{ $score }}</h1>
                            @else
                                <h1 class="text-5xl font-bold severity-low">{{ $score }}</h1>
                            @endif
                        @endif
                        <span class="text-xs text-gray-500">CVSS Score</span>
                    </div>

                    <!-- CVE Details -->
                    <h2 class="text-2xl font-semibold text-blue-700 mb-2">{{ $vuln->cve_id }}</h2>
                    <p class="text-gray-600 mt-2 mb-4 text-sm">
                        {{ Str::limit($vuln->description, 120, '...') }}
                    </p>
                    <p class="text-sm text-gray-500">
                        Published:
                        <span class="font-semibold">
                            {{ \Carbon\Carbon::parse($vuln->published_at)->format('d M Y') }}
                        </span>
                    </p>
                    <a href="{{ $vuln->detail_url }}"
                        class="text-blue-500 hover:text-blue-700 mt-4 inline-block text-sm font-semibold">
                         More Info
                     </a>
                    {{-- <a href="{{ route('cve.detail', ['cveId' => $vuln->cve_id]) }}"
                        class="text-blue-500 hover:text-blue-700 mt-4 inline-block text-sm font-semibold">
                         More Info
                     </a> --}}

                </div>
            @endforeach
        </div>
    @else
        <p class="text-center text-gray-500">No other vulnerabilities found.</p>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/flowbite@1.5.0/dist/flowbite.min.js"></script>
@vite('resources/js/app.js')
@endsection
