@extends('layouts.app')

@section('content')
<body class="bg-black p-5 text-white">
    <div class="container mx-auto bg-gray-900 p-6 rounded-lg shadow-lg mt-5">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-extrabold text-white">Vulnerabilities</h1>
            <div class="flex items-center space-x-4">
                <div class="text-lg text-gray-300">
                    <span>Total</span>
                    <span class="font-bold text-indigo-400">{{ number_format($total) }} CVE</span>
                </div>
            </div>
        </div>

        <!-- Search Form -->
        <div class="flex justify-end mb-4">
            <form method="GET" action="{{ route('opencve.index') }}" class="flex items-center bg-gray-800 rounded-md overflow-hidden">
                <!-- Dropdown Filter -->
                <div class="relative">
                    <select
                        name="filter_type"
                        class="appearance-none border-none bg-gray-800 text-white text-base px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="cve" {{ request('filter_type') == 'cve' ? 'selected' : '' }}>Search by CVE ID</option>
                        <option value="vendors" {{ request('filter_type') == 'vendors' ? 'selected' : '' }}>Search by Vendors</option>
                        <option value="products" {{ request('filter_type') == 'products' ? 'selected' : '' }}>Search by Products</option>
                    </select>
                    <span class="absolute right-3 top-2 text-gray-400 pointer-events-none">
                        <i class="fas fa-chevron-down"></i>
                    </span>
                </div>

                <!-- Input Query -->
                <input
                    type="text"
                    name="query"
                    value="{{ request('query') }}"
                    placeholder="Enter CVE ID or Product..."
                    class="px-4 py-2 w-64 bg-gray-800 text-white text-base border-none focus:outline-none focus:ring-2 focus:ring-indigo-500"
                />

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="flex items-center bg-indigo-600 text-white text-base px-4 py-2 hover:bg-indigo-700 focus:ring-2 focus:ring-indigo-500">
                    <i class="fas fa-search mr-2"></i> Search
                </button>
            </form>
        </div>

        <!-- Error Notification -->
        @if (isset($error))
            <div class="mb-4 text-red-400 text-base">{{ $error }}</div>
        @endif

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-gray-800 border border-gray-700 rounded-md">
                <thead>
                    <tr class="bg-gray-700 text-gray-200 uppercase text-base leading-normal">
                        <th class="py-3 px-6 text-left">CVE</th>
                        <th class="py-3 px-6 text-left">Description</th>
                        <th class="py-3 px-6 text-left">Updated</th>
                    </tr>
                </thead>
                <tbody class="text-gray-100 text-base font-light">
                    @forelse ($results as $result)
                        <tr class="border-b border-gray-700 hover:bg-gray-700">
                            @if (isset($result['cve_id']))
                                <td class="py-3 px-6">
                                    <a href="{{ route('cve.show', ['id' => $result['cve_id']]) }}" class="text-indigo-400 font-medium hover:underline">
                                        {{ $result['cve_id'] }}
                                    </a>
                                </td>
                                <td class="py-3 px-6">{{ $result['description'] }}</td>
                            @elseif (isset($result['name']))
                                <td class="py-3 px-6" colspan="2">{{ $result['name'] }}</td>
                            @endif
                            <td class="py-3 px-6">
                                {{ isset($result['updated_at']) ? \Carbon\Carbon::parse($result['updated_at'])->format('Y-m-d') : 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center py-3 text-gray-400">No data available.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4 flex justify-center">
            @php
                $lastPage = ceil($total / 10);
                $startPage = max(1, $currentPage - 2);
                $endPage = min($lastPage, $currentPage + 2);
            @endphp
            <nav class="inline-flex shadow-sm" aria-label="Pagination">
                @if ($currentPage > 1)
                    <a href="{{ url()->current() }}?page={{ $currentPage - 1 }}" class="py-2 px-3 bg-gray-700 text-white border border-gray-600 rounded-l hover:bg-gray-600">&laquo; Previous</a>
                @else
                    <span class="py-2 px-3 bg-gray-800 text-gray-500 rounded-l cursor-not-allowed">&laquo; Previous</span>
                @endif

                @for ($i = $startPage; $i <= $endPage; $i++)
                    @if ($i == $currentPage)
                        <span class="py-2 px-3 bg-indigo-600 text-white">{{ $i }}</span>
                    @else
                        <a href="{{ url()->current() }}?page={{ $i }}" class="py-2 px-3 bg-gray-700 text-white border border-gray-600 hover:bg-gray-600">{{ $i }}</a>
                    @endif
                @endfor

                @if ($currentPage < $lastPage)
                    <a href="{{ url()->current() }}?page={{ $currentPage + 1 }}" class="py-2 px-3 bg-gray-700 text-white border border-gray-600 rounded-r hover:bg-gray-600">Next &raquo;</a>
                @else
                    <span class="py-2 px-3 bg-gray-800 text-gray-500 rounded-r cursor-not-allowed">Next &raquo;</span>
                @endif
            </nav>
        </div>
    </div>
</body>
@endsection
