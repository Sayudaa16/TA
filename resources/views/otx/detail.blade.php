@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-3xl font-bold text-gray-900">{{ $pulse['name'] ?? 'Unknown Pulse' }}</h2>
    <p class="text-sm text-gray-500">
        <span class="font-semibold">By:</span> {{ $pulse['author_name'] ?? 'Unknown' }} |
        <span class="font-semibold">Created:</span> {{ isset($pulse['created']) ? date('d M Y', strtotime($pulse['created'])) : 'Unknown' }} |
        <span class="font-semibold">Modified:</span> {{ isset($pulse['modified']) ? date('d M Y', strtotime($pulse['modified'])) : 'Unknown' }}
    </p>

    <div class="bg-white shadow-md rounded-lg p-6 mt-4">
        <h3 class="text-xl font-semibold text-gray-900">Description</h3>
        <p class="text-gray-700 mt-2">{{ $pulse['description'] ?? 'No description available.' }}</p>

        <div class="grid grid-cols-2 gap-4 mt-6">
            <div>
                <h3 class="text-lg font-semibold">Pulse Details</h3>
                <p class="text-gray-700"><span class="font-bold">ID:</span> {{ $pulse['id'] ?? 'N/A' }}</p>
                <p class="text-gray-700"><span class="font-bold">Revision:</span> {{ $pulse['revision'] ?? 'N/A' }}</p>
                <p class="text-gray-700"><span class="font-bold">Public:</span> {{ isset($pulse['public']) && $pulse['public'] ? 'Yes' : 'No' }}</p>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Adversary Information</h3>
                <p class="text-gray-700"><span class="font-bold">Adversary:</span> {{ $pulse['adversary'] ?? 'Unknown' }}</p>
            </div>
        </div>

        <h3 class="text-xl font-semibold text-gray-900 mt-6">Indicators</h3>
        <div class="overflow-x-auto mt-2">
            <table class="min-w-full bg-white border border-gray-200">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-2 px-3 text-left">ID</th>
                        <th class="py-2 px-3 text-left">Type</th>
                        <th class="py-2 px-3 text-left">Indicator</th>
                        <th class="py-2 px-3 text-left">Created</th>
                        <th class="py-2 px-3 text-left">Title</th>
                        <th class="py-2 px-3 text-left">Description</th>
                        <th class="py-2 px-3 text-left">Active</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    @foreach ($pulse['indicators'] ?? [] as $indicator)
                    <tr class="border-b border-gray-200">
                        <td class="py-2 px-3">{{ $indicator['id'] ?? '-' }}</td>
                        <td class="py-2 px-3">{{ $indicator['type'] ?? '-' }}</td>
                        <td class="py-2 px-3 font-mono">{{ $indicator['indicator'] ?? '-' }}</td>
                        <td class="py-2 px-3">{{ isset($indicator['created']) ? date('d M Y', strtotime($indicator['created'])) : '-' }}</td>
                        <td class="py-2 px-3">{{ $indicator['title'] ?? '-' }}</td>
                        <td class="py-2 px-3">{{ $indicator['description'] ?? '-' }}</td>
                        <td class="py-2 px-3">{{ isset($indicator['is_active']) && $indicator['is_active'] ? '✅' : '❌' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
