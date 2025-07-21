<x-body>
    <form method="POST" action="{{ route('admin.products.update', $product->uuid) }}">
        @csrf
        @method('PUT')

        <div class="space-y-6 pr-40 pl-40">
            <div>
                <label class="block text-xl font-medium text-gray-700">Product Name</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="p-4 block w-full rounded-md border-gray-300">
            </div>

            <div>
                <label class="block text-xl font-medium">Product Type</label>
                <select name="product_type_id" class="p-4 block w-full rounded-md border-gray-300">
                    <option value="">Select existing type</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}" {{ $product->product_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xl font-medium">Price</label>
                <input type="number" name="price" value="{{ old('price', $product->price) }}" required class="p-4 block w-full rounded-md border-gray-300">
            </div>

            <div>
                <label class="block text-xl font-medium">Release Date</label>
                <input type="date" name="releaseDate" value="{{ old('releaseDate', \Carbon\Carbon::parse($product->release_date)->format('Y-m-d')) }}" required class="p-4 block w-full rounded-md border-gray-300">
            </div>

            <div>
                <label class="block text-xl font-medium">Manufacturer</label>
                <select name="manufacturer_id" class="p-4 block w-full rounded-md border-gray-300">
                    @foreach($manufacturers as $manufacturer)
                        <option value="{{ $manufacturer->id }}" {{ $product->manufacturer_id == $manufacturer->id ? 'selected' : '' }}>{{ $manufacturer->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xl font-medium">Description</label>
                <textarea name="description" rows="3" class="p-4 block w-full rounded-md border-gray-300">{{ old('description', $product->description) }}</textarea>
            </div>

            <div>
                <label class="block text-xl font-semibold">Services</label>
                <div id="services">
                    @foreach($product->services as $index => $service)
                        <div class="flex gap-2 items-center mb-2">
                            <input type="text" name="services[{{ $index }}][name]" value="{{ $service->name }}" class="w-40 rounded border-gray-300" required>
                            <input type="number" name="services[{{ $index }}][price]" value="{{ $service->pivot->price }}" class="w-24 rounded border-gray-300" required>
                            <input type="number" name="services[{{ $index }}][daysNeeded]" value="{{ $service->pivot->days_needed }}" class="w-20 rounded border-gray-300" required>
                            <button type="button" onclick="this.parentElement.remove()" class="text-red-500 text-xl">Remove</button>
                        </div>
                    @endforeach
                </div>
                <button type="button" onclick="addService()" class="mt-2 text-indigo-600 hover:underline">+ Add Service</button>
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit" class="rounded bg-indigo-600 px-4 py-2 text-white">Update Product</button>
            </div>
        </div>
    </form>

    <script>
        let serviceIndex = {{ $product->services->count() }};
        function addService() {
            const container = document.getElementById('services');
            const div = document.createElement('div');
            div.classList.add('flex', 'gap-2', 'items-center', 'mb-2');
            div.innerHTML = `
                <input type="text" name="services[${serviceIndex}][name]" placeholder="Service name" class="w-40 rounded border-gray-300" required />
                <input type="number" name="services[${serviceIndex}][price]" placeholder="Price" class="w-24 rounded border-gray-300" required />
                <input type="number" name="services[${serviceIndex}][daysNeeded]" placeholder="Days" class="w-20 rounded border-gray-300" required />
                <button type="button" onclick="this.parentElement.remove()" class="text-red-500 text-xl">Remove</button>
            `;
            container.appendChild(div);
            serviceIndex++;
        }

    </script>

</x-body>
