@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <div class="bg-white shadow-md rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-6">CVE Detail: {{ $data['cveMetadata']['cveId'] }}</h1>

        <!-- Description -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Description:</h3>
            <div class="space-y-2">
                @foreach($data['containers']['cna']['descriptions'] as $description)
                    <p><strong>{{ $description['lang'] }}:</strong> {{ $description['value'] }}</p>
                @endforeach
            </div>
        </div>

        <!-- Problem Types -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Problem Types:</h3>
            <ul class="list-disc pl-5 space-y-1">
                @foreach($data['containers']['cna']['problemTypes'] as $problem)
                    @foreach($problem['descriptions'] as $desc)
                        <li>{{ $desc['description'] }} (CWE-{{ $desc['cweId'] }})</li>
                    @endforeach
                @endforeach
            </ul>
        </div>

        <!-- Affected Products -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Affected Products:</h3>
            <ul class="list-disc pl-5 space-y-2">
                @foreach($data['containers']['cna']['affected'] as $affected)
                    <li>
                        <strong>{{ $affected['vendor'] }}</strong> - {{ $affected['product'] }}
                        <ul class="pl-5 space-y-1">
                            @foreach($affected['versions'] as $version)
                                <li>{{ $version['version'] }} - Status: {{ $version['status'] }}</li>
                            @endforeach
                        </ul>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- CVSS Metrics -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">CVSS Metrics:</h3>
            <table class="table-auto w-full border-collapse border border-gray-200">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-200 px-4 py-2">Metric</th>
                        <th class="border border-gray-200 px-4 py-2">Version</th>
                        <th class="border border-gray-200 px-4 py-2">Base Score</th>
                        <th class="border border-gray-200 px-4 py-2">Severity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['containers']['cna']['metrics'] as $metric)
                        @foreach($metric as $key => $value)
                            <tr>
                                <td class="border border-gray-200 px-4 py-2">{{ $key }}</td>
                                <td class="border border-gray-200 px-4 py-2">{{ $value['version'] }}</td>
                                <td class="border border-gray-200 px-4 py-2">{{ $value['baseScore'] }}</td>
                                <td class="border border-gray-200 px-4 py-2">{{ $value['baseSeverity'] ?? 'Not Available' }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Timeline -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Timeline:</h3>
            <ul class="list-disc pl-5 space-y-1">
                @foreach($data['containers']['cna']['timeline'] as $timeline)
                    <li><strong>{{ $timeline['time'] }} ({{ $timeline['lang'] }}):</strong> {{ $timeline['value'] }}</li>
                @endforeach
            </ul>
        </div>

        <!-- Credits -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Credits:</h3>
            <ul class="list-disc pl-5 space-y-1">
                @foreach($data['containers']['cna']['credits'] as $credit)
                    <li>{{ $credit['value'] }} ({{ $credit['type'] }})</li>
                @endforeach
            </ul>
        </div>

        <!-- References -->
        <div>
            <h3 class="text-lg font-semibold mb-2">References:</h3>
            <ul class="list-disc pl-5 space-y-1">
                @foreach($data['containers']['cna']['references'] as $reference)
                    <li>
                        <a href="{{ $reference['url'] }}" target="_blank" class="text-blue-500 hover:underline">
                            {{ $reference['name'] ?? 'No Name Available' }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
