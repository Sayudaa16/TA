@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-10">
        <h2 class="text-2xl font-bold mb-6">Latest News</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($latestNews as $article)
                <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col">
                    <img src="{{ $article['url_to_image'] ?? 'https://via.placeholder.com/400x200?text=No+Image' }}"
                         class="w-full h-48 object-cover" alt="News Image">
                    <div class="p-4 flex flex-col flex-grow">
                        <h5 class="text-lg font-semibold mb-2">{{ $article['title'] }}</h5>
                        <p class="text-gray-700 mb-4">{{ Str::limit($article['description'], 100) }}</p>
                        <a href="{{ $article['url'] }}" target="_blank"
                           class="mt-auto bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 text-center">Read More</a>
                    </div>
                </div>
            @endforeach
        </div>

        <h2 class="text-2xl font-bold my-10">Other News</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($otherNews as $article)
                <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col">
                    <img src="{{ $article['url_to_image'] ?? 'https://via.placeholder.com/400x200?text=No+Image' }}"
                         class="w-full h-48 object-cover" alt="News Image">
                    <div class="p-4 flex flex-col flex-grow">
                        <h5 class="text-lg font-semibold mb-2">{{ $article['title'] }}</h5>
                        <p class="text-gray-700 mb-4">{{ Str::limit($article['description'], 100) }}</p>
                        <a href="{{ $article['url'] }}" target="_blank"
                           class="mt-auto bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700 text-center">Read More</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
