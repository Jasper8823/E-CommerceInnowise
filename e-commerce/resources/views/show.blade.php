<x-body>
    <div class="w-full px-6 py-10 bg-white space-y-6 ml-[300px]">
        <h2 class="text-4xl font-bold text-gray-900">{{ $product->name }}</h2>

        <p class="text-lg text-gray-600">By {{ $product -> manufacturer-> name  }}</p>

        <div class="text-base text-gray-800 leading-relaxed">{{ $product->description }}</div>

        <p class="text-3xl font-semibold text-green-700">${{ number_format($product->price, 2) }}</p>

        <p class="text-sm text-gray-500">Released: {{ date('d F Y', strtotime($product->releaseDate)) }}</p>
        <h3 class="text-2xl font-semibold mt-10">Services:</h3>
        <ul role="list" class="divide-y divide-gray-200">
            @foreach($product -> services as $service)
                <div class="mt-4 w-[200px] border border-gray-200 rounded-xl p-4 shadow-sm bg-white">
                    <div class="space-y-2">
                        <div class="font-medium text-gray-900 text-lg">{{ $service->name }}</div>
                        <div class="font-semibold text-green-700">Price: ${{ number_format($service->price, 2) }}</div>
                    </div>
                </div>
            @endforeach
        </ul>
    </div>
</x-body>

