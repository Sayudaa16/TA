@extends('layouts.app')

@section('content')
    @if (session('error'))
        <div class="bg-red-700 border border-red-500 text-white text-lg px-4 py-3 rounded relative mb-6" role="alert">
            <strong class="font-bold">Error!</strong>
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Form Pencarian -->
    <form action="{{ route('otx.index') }}" method="GET" class="mb-6 flex mt-5">
        <input
            type="text"
            name="q"
            value="{{ request('q') }}"
            placeholder="Search Pulses..."
            class="w-full p-4 text-lg border border-gray-600 rounded-l-md bg-gray-800 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
        <button
            type="submit"
            class="bg-blue-600 text-white text-lg px-6 rounded-r-md hover:bg-blue-700 transition"
        >
            Search
        </button>
    </form>

    @if (!$searchResults)
        <h3 class="text-3xl font-bold text-white mb-4">Recommendation Pulses</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($pulses['results'] ?? [] as $pulse)
                @php
                    $pulseName = $pulse['name'] ?? 'Untitled';
                    $pulseDesc = \Illuminate\Support\Str::limit($pulse['description'] ?? 'No description available', 120, '...');
                    $author = $pulse['author_name'] ?? 'Unknown';
                @endphp
                <div class="shadow-lg rounded-lg overflow-hidden transition-transform transform hover:scale-105 flex flex-col h-full">
                    <div class="p-6 bg-gray-800 rounded-lg flex flex-col justify-between h-full">
                        <div>
                            <h3 class="text-2xl font-bold text-white">{{ $pulseName }}</h3>
                            <p class="text-lg text-gray-300 mt-3">{{ $pulseDesc }}</p>
                            <p class="text-md text-gray-400 mt-4">
                                By: <span class="font-semibold">{{ $author }}</span>
                            </p>
                        </div>
                        <div class="mt-6 flex gap-4">
                            <a
                                href="{{ route('otx.download', $pulse['id']) }}"
                                class="block text-center bg-green-600 text-white text-lg font-bold py-3 px-4 rounded-md hover:bg-green-700 transition"
                            >
                                Download PDF
                            </a>
                            <a
                                href="{{ route('otx.show', $pulse['id']) }}"
                                class="block text-center bg-blue-600 text-white text-lg font-bold py-3 px-4 rounded-md hover:bg-blue-700 transition"
                            >
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if ($searchResults)
        <h3 class="text-3xl font-bold text-white mt-10 mb-4">
            Search Results for "{{ request('q') }}"
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($searchResults['results'] ?? [] as $pulse)
                @php
                    $pulseName = $pulse['name'] ?? 'Untitled';
                    $pulseDesc = \Illuminate\Support\Str::limit($pulse['description'] ?? 'No description available', 120, '...');
                    $author = $pulse['author_name'] ?? 'Unknown';
                @endphp
                <div class="shadow-lg rounded-lg overflow-hidden transition-transform transform hover:scale-105 flex flex-col h-full">
                    <div class="p-6 bg-gray-800 rounded-lg flex flex-col justify-between h-full">
                        <div>
                            <h3 class="text-2xl font-bold text-white">{{ $pulseName }}</h3>
                            <p class="text-lg text-gray-300 mt-3">{{ $pulseDesc }}</p>
                            <p class="text-md text-gray-400 mt-4">
                                By: <span class="font-semibold">{{ $author }}</span>
                            </p>
                        </div>
                        <div class="mt-6 flex gap-4">
                            <a
                                href="{{ route('otx.download', $pulse['id']) }}"
                                class="block text-center bg-green-600 text-white text-lg font-bold py-3 px-4 rounded-md hover:bg-green-700 transition"
                            >
                                Download PDF
                            </a>
                            <a
                                href="{{ route('otx.show', $pulse['id']) }}"
                                class="block text-center bg-blue-600 text-white text-lg font-bold py-3 px-4 rounded-md hover:bg-blue-700 transition"
                            >
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
