<x-body>
    <div class="px-[200px] mb-6">
        <x-filtration :types="$types" :rates="$rates"></x-filtration>
    </div>
    <x-currency-converter :rates="$rates"></x-currency-converter>
    <div class="bg-gray-100 py-8 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                @if(count($products) > 0)
                    @foreach($products as $product)
                            <div class="bg-white rounded-lg shadow p-4 flex flex-col justify-between">
                                <div onclick="window.location.href='/products/{{$product->uuid}}'">
                                    <h2 class="text-lg font-semibold text-gray-800">{{ $product->name }}</h2>
                                    <p class="text-sm text-gray-500 mb-2">{{ $product -> manufacturer-> name }}</p>
                                    <p class="text-gray-700 text-sm mb-4">{{ $product->description }}</p>
                                </div>
                                <div>
                                    <div onclick="window.location.href='/products/{{$product->uuid}}'">
                                        <p class="update-price text-green-600 font-bold text-base mb-1">{{$product->getPrice($rate, $product->price)}}</p>
                                        <p class="text-xs text-gray-400">Released: {{ date('d F Y', strtotime($product->release_date)) }}</p>
                                    </div>
                                </div>
                            </div>
                    @endforeach
                    <div class="mt-6 col-span-full">
                        {{ $products->links() }}
                    </div>
                @else
                    <p class="text-center text-gray-500">No products found.</p>
                @endif
            </div>
        </div>
    </div>
</x-body>
