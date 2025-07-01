@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 mt-5">
    <!-- Top Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white p-6 shadow-lg rounded-2xl text-center">
            <h2 class="text-gray-500 text-lg font-medium">News Updates Today</h2>
            <p class="text-4xl font-extrabold text-blue-600 mt-2">{{ $newsToday }}</p>
        </div>
        <div class="bg-white p-6 shadow-lg rounded-2xl text-center">
            <h2 class="text-gray-500 text-lg font-medium">Top Trending Category</h2>
            <p class="text-2xl font-bold text-green-600 mt-2">{{ $topCategory }}</p>
        </div>
        <div class="bg-white p-6 shadow-lg rounded-2xl text-center">
            <h2 class="text-gray-500 text-lg font-medium">Top News Source</h2>
            <p class="text-2xl font-bold text-red-600 mt-2">{{ $topNewsSource }}</p>
        </div>
    </div>

    <!-- Bottom Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-6 shadow-lg rounded-2xl">
            <h2 class="text-xl font-semibold mb-4">Top 10 Categories</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 shadow-md rounded-lg">
                    <thead>
                        <tr class="bg-blue-600 text-white">
                            <th class="py-3 px-6 text-left">#</th>
                            <th class="py-3 px-6 text-left">Category</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($topCategoriesLabels as $index => $category)
                        <tr class="border-b hover:bg-blue-50">
                            <td class="py-3 px-6 font-semibold text-gray-700">{{ $index + 1 }}</td>
                            <td class="py-3 px-6 font-semibold text-blue-600">{{ $category }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="bg-white p-6 shadow-lg rounded-2xl">
            <h2 class="text-xl font-semibold mb-4">Trending Categories</h2>
            <div id="wordCloud" class="flex flex-wrap gap-3 justify-center"></div>
        </div>
    </div>
</div>

<!-- Word Cloud Script -->
<script>
    var words = @json($topCategoriesLabels);
    var container = document.getElementById("wordCloud");
    words.forEach(word => {
        let span = document.createElement("span");
        span.textContent = word;
        span.classList.add("px-4", "py-2", "rounded-full", "shadow-md", "font-semibold", "text-white");
        span.style.backgroundColor = `hsl(${Math.random() * 360}, 70%, 50%)`;
        span.style.fontSize = `${Math.random() * 10 + 14}px`;
        container.appendChild(span);
    });
</script>
@endsection
