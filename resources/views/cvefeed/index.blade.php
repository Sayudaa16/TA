@extends('layouts.app')

@section('title', 'IP Vulnerability Scanner')

@section('content')
    <style>
        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
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
    </style>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-center mb-6 text-blue-600">Latest CVE Vulnerabilities</h1>

        <!-- Menampilkan pesan error jika ada -->
        @if(session('error'))
            <div class="bg-red-500 text-white p-4 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Memeriksa apakah 'vulnerabilities' ada dan memiliki item -->
        @isset($vulnerabilities)
            @if(count($vulnerabilities) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    @foreach($vulnerabilities as $vuln)
                        <div class="card bg-white p-6 rounded-lg shadow-lg hover:shadow-xl">
                            <!-- CVSS Score and Badge -->
                            <div class="text-center mb-4">
                                @if($vuln['CVSS_Score'] == 'Not Available' || $vuln['CVSS_Score'] == '0.0')
                                    <h1 class="text-5xl font-bold text-gray-400">0.0</h1>
                                @else
                                    @if($vuln['CVSS_Score'] >= 7.0)
                                        <h1 class="text-5xl font-bold severity-high">{{ $vuln['CVSS_Score'] }}</h1>
                                    @elseif($vuln['CVSS_Score'] >= 4.0)
                                        <h1 class="text-5xl font-bold severity-medium">{{ $vuln['CVSS_Score'] }}</h1>
                                    @else
                                        <h1 class="text-5xl font-bold severity-low">{{ $vuln['CVSS_Score'] }}</h1>
                                    @endif
                                @endif
                                <span class="text-xs text-gray-500">CVSS_Score</span>
                            </div>

                            <!-- CVE Details -->
                            <h2 class="text-2xl font-semibold text-blue-700">{{ $vuln['Title'] }}</h2>
                            <p class="text-gray-600 mt-2 mb-4 text-sm">{{ $vuln['Description'] ?? 'No description available' }}</p>
                            <p class="text-sm text-gray-500">Published: <span class="font-semibold">{{ $vuln['Published'] }}</span></p>
                            <a href="{{ $vuln['Detail_URL'] }}" target="_blank" class="text-blue-500 hover:text-blue-700 mt-4 inline-block text-sm font-semibold">More Info</a>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-gray-500">No vulnerabilities found at this moment.</p>
            @endif
        @else
            <p class="text-center text-red-500">Error: Data not available.</p>
        @endisset
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flowbite@1.5.0/dist/flowbite.min.js"></script>
    @vite('resources/js/app.js')
</body>
@endsection
