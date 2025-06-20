<x-body>
    <div class="bg-gray-100 py-8 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                @if(count($products) > 0)
                    @foreach($products as $product)
                            <div class="bg-white rounded-lg shadow p-4 flex flex-col justify-between" onclick="window.location.href='/products/{{$product->id}}'">
                                <div>
                                    <h2 class="text-lg font-semibold text-gray-800">{{ $product->name }}</h2>
                                    <p class="text-sm text-gray-500 mb-2">{{ $product -> manufacturer-> name }}</p>
                                    <p class="text-gray-700 text-sm mb-4">{{ $product->description }}</p>
                                </div>
                                <div>
                                    <p class="text-green-600 font-bold text-base mb-1">${{$product->price}}</p>
                                    <p class="text-xs text-gray-400">Released: {{ date('d F Y', strtotime($product->releaseDate)) }}</p>
                                </div>
                            </div>
                    @endforeach
                @else
                    <p class="text-center text-gray-500">No products found.</p>
                @endif
            </div>
        </div>
    </div>
</x-body>
