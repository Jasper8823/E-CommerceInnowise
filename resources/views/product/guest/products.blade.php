<x-body>
    <div class="px-[200px] mb-6">
        <form method="GET" action="{{ url()->current() }}" class="grid grid-cols-1 md:grid-cols-6 gap-4 items-end">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Sort By</label>
                <select name="sort" class="mt-1 block w-full rounded-md shadow-sm
                focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2">
                    <option value="">Default</option>
                    <option value="price_asc" @selected(request('sort') === 'price_asc')>Price: Low to High</option>
                    <option value="price_desc" @selected(request('sort') === 'price_desc')>Price: High to Low</option>
                    <option value="release_asc" @selected(request('sort') === 'release_asc')>Release Date: Oldest First</option>
                    <option value="release_desc" @selected(request('sort') === 'release_desc')>Release Date: Newest First</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Type</label>
                <select name="type" class="mt-1 block w-full rounded-md shadow-sm
               focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2">
                    <option value="">All types</option>
                    @foreach ($types as $type)
                        <option value="{{ $type->name }}" @selected(request('type') === $type->name)>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" name="name" value="{{ request('name') }}" placeholder="Search by name..."
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Min Price</label>
                <input type="number" step="10" min="0" name="min_price" value="{{ request('min_price') }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Max Price</label>
                <input type="number" step="10" min="0" name="max_price" value="{{ request('max_price') }}"
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2">
            </div>

            <div class="self-end">
                <button type="submit"
                        class="w-full px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-500 shadow">
                    Filter
                </button>
            </div>
        </form>

    </div>
    <div class="bg-gray-100 py-8 px-4">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                @if(count($products) > 0)
                    @foreach($products as $product)
                            <div class="bg-white rounded-lg shadow p-4 flex flex-col justify-between">
                                <div onclick="window.location.href='/products/{{$product->uuId}}'">
                                    <h2 class="text-lg font-semibold text-gray-800">{{ $product->name }}</h2>
                                    <p class="text-sm text-gray-500 mb-2">{{ $product -> manufacturer-> name }}</p>
                                    <p class="text-gray-700 text-sm mb-4">{{ $product->description }}</p>
                                </div>
                                <div>
                                    <div onclick="window.location.href='/products/{{$product->uuId}}'">
                                        <p class="text-green-600 font-bold text-base mb-1">${{$product->price}}</p>
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
