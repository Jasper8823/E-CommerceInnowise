<x-body>
    <form style="position: absolute; top: 135px; right: 40px" action="{{ route('admin.products.export') }}" method="POST">
        @csrf
        <button
            type="submit"
            class="mb-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-500 shadow">
            Export
        </button>
    </form>
    <div class="px-[200px] mb-6">
        <x-filtration :types="$types"></x-filtration>
    </div>
    <div class="bg-gray-100 py-8 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                @if(count($products) > 0)
                    @foreach($products as $product)
                            <div class="bg-white rounded-lg shadow p-4 flex flex-col justify-between">
                                <div onclick="window.location.href='/admin/products/{{$product->uuId}}'">
                                    <h2 class="text-lg font-semibold text-gray-800">{{ $product->name }}</h2>
                                    <p class="text-sm text-gray-500 mb-2">{{ $product -> manufacturer-> name }}</p>
                                    <p class="text-gray-700 text-sm mb-4">{{ $product->description }}</p>
                                </div>
                                <div>
                                    <div onclick="window.location.href='/products/{{$product->uuId}}'">
                                        <p class="update-price text-green-600 font-bold text-base mb-1"
                                           data-price="{{ $product->price }}">
                                            ${{ $product->price }}
                                        </p>
                                        <p class="text-xs text-gray-400">Released: {{ date('d F Y', strtotime($product->release_date)) }}</p>
                                    </div>
                                        <form method="POST" action="/products/{{ $product->id }}" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="w-full mt-2 px-3 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-500 rounded-md transition-shadow shadow hover:shadow-lg"
                                            >
                                                Delete Product
                                            </button>
                                        </form>
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
