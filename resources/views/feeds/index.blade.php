<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Latest Cybersecurity News</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@1.6.1/dist/flowbite.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-center mb-6">Latest Cybersecurity News</h1>

        @if(count($feeds) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($feeds as $feed)
                <div class="max-w-sm rounded overflow-hidden shadow-lg bg-white">
                    @if(isset($feed['title']))
                        <h2 class="font-bold text-xl mb-2">{{ $feed['title'] }}</h2>
                    @else
                        <h2 class="font-bold text-xl mb-2">No Title Available</h2>
                    @endif

                    @if(isset($feed['image_url']))
                        <img class="w-full h-48 object-cover" src="{{ $feed['image_url'] }}" alt="news image">
                    @else
                        <img class="w-full h-48 object-cover" src="default-image.jpg" alt="default image">
                    @endif

                    <div class="px-6 py-4">
                        @if(isset($feed['description']))
                            <p class="text-gray-700 text-base mb-4">{{ Str::limit($feed['description'], 150) }}</p>
                        @else
                            <p class="text-gray-700 text-base mb-4">No Description Available</p>
                        @endif
                        {{-- <a href="{{ $feed['link'] }}" target="_blank" class="text-blue-600 hover:text-blue-800">Read more</a> --}}
                    </div>
                    <div class="px-6 py-4">
                        @if(isset($feed['pubDate']))
                            <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($feed['pubDate'])->toFormattedDateString() }}</p>
                        @else
                            <p class="text-sm text-gray-500">No Date Available</p>
                        @endif
                    </div>
                </div>
            @endforeach



            </div>
        @else
            <p class="text-center text-gray-700">No news available.</p>
        @endif
    </div>
</body>
</html>
