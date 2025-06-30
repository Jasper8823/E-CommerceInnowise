<x-body>
    <form method="POST" action="/products">
        @csrf
        <div class="space-y-6 pr-40 pl-40">

            <div>
                <label for="name" class="block text-xl font-medium text-gray-700">Product Name</label>
                <input type="text" name="name" id="name" required
                       class="p-4 mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm text-xl">
            </div>

            <div>
                <label class="block text-xl font-medium text-gray-700">Product Type</label>
                <select name="product_type_id" id="product_type_id"
                        class="p-4 mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm text-base">
                    <option value="">Select existing type</option>
                    @foreach($types as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
                <p class="mt-2 text-xl text-gray-500">or create new type:</p>
                <input type="text" name="new_product_type" placeholder="New product type"
                       class="p-4 mt-1 block w-full border-gray-300 rounded-md shadow-sm sm text-base">
            </div>

            <div>
                <label for="price" class="block text-xl font-medium text-gray-700">Price ($)</label>
                <input type="number" name="price" id="price" min="0" required
                       class="p-4 mt-1 block w-full border-gray-300 rounded-md shadow-sm sm text-base">
            </div>

            <div>
                <label for="releaseDate" class="block text-xl font-medium text-gray-700">Release Date</label>
                <input type="date" name="releaseDate" id="releaseDate" required
                       class="p-4 mt-1 block w-full border-gray-300 rounded-md shadow-sm sm text-base">
            </div>

            <div>
                <label for="company_id" class="block text-xl font-medium text-gray-700">Manufacturer</label>
                <select name="company_id" id="company_id" required
                        class="p-4 mt-1 block w-full border-gray-300 rounded-md shadow-sm sm text-base">
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                    @endforeach
                </select>
                <p class="mt-2 text-xl text-gray-500">or create new manufacturer:</p>
                <input type="text" name="new_company" placeholder="New company"
                       class="p-4 mt-1 block w-full border-gray-300 rounded-md shadow-sm sm text-base">
            </div>

            <div>
                <label for="description" class="block text-xl font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="3"
                          class="p-4 mt-1 block w-full border-gray-300 rounded-md shadow-sm sm text-base"></textarea>
            </div>

            <div>
                <label class="block text-xl font-semibold text-gray-800 mb-2">Connected Services</label>

                @php
                    $defaultServices = ['Service', 'Delivery', 'Installation', 'Configuration'];
                @endphp

                @foreach($defaultServices as $service)
                    <div class="flex items-center gap-3 mb-2">
                        <input type="checkbox" name="services[{{ $service }}][enabled]" value="1" class="h-4 w-4 text-indigo-600">
                        <span class="capitalize">{{ $service }}</span>
                        <input type="number" min="0" name="services[{{ $service }}][price]" placeholder="Price" class="ml-2 w-24 rounded border-gray-300" />
                        <input type="number" min="0" name="services[{{ $service }}][daysNeeded]" placeholder="Days" class="w-20 rounded border-gray-300" />
                    </div>
                @endforeach

                <div class="mt-4">
                    <label class="block text-xl font-medium text-gray-700">Add Custom Service</label>
                    <div id="custom-services"></div>
                    <button type="button" onclick="addCustomService()" class="mt-2 text-indigo-600 text-base hover:underline">+ Add custom service</button>
                </div>
            </div>

            @if($errors->any())
                <div class="text-red-600">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="flex justify-end pt-4">
                <button type="submit"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-base font-semibold text-white hover:bg-indigo-500">
                    Create Product
                </button>
            </div>
        </div>
    </form>

    <script>
        let customServiceIndex = 0;
        function addCustomService() {
            const container = document.getElementById('custom-services');
            const div = document.createElement('div');
            div.classList.add('flex', 'gap-2', 'items-center', 'mb-2');
            div.innerHTML = `
                <input type="text" name="custom_services[${customServiceIndex}][name]" placeholder="Service name" class="w-40 rounded border-gray-300" required />
                <input type="number" min="0" name="custom_services[${customServiceIndex}][price]" placeholder="Price" class="w-24 rounded border-gray-300" required />
                <input type="number" min="0" name="custom_services[${customServiceIndex}][daysNeeded]" placeholder="Days" class="w-20 rounded border-gray-300" required />
                <button type="button" onclick="this.parentElement.remove()" class="text-red-500 text-xl hover:underline">Remove</button>
            `;
            container.appendChild(div);
            customServiceIndex++;
        }
    </script>
</x-body>
