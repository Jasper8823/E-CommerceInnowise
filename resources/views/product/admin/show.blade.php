<x-body>
    <script>
        const exchangeRates = {
            USD: {{ $rates['USD'] ?? 'null' }},
            EUR: 1,
            PLN: {{ $rates['PLN'] ?? 'null' }}
        };

        document.addEventListener("DOMContentLoaded", function () {
            const currencySelector = document.getElementById("currency-selector");
            const prices = document.querySelectorAll(".update-price");
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            const totalPriceElem = document.getElementById('totalPrice');
            const productPrice = {{ $product->price }};

            function formatPrice(value, currency) {
                return new Intl.NumberFormat('en-US', {
                    style: 'currency',
                    currency: currency
                }).format(value);
            }

            function updatePrices(currency) {
                const rate = exchangeRates[currency];
                prices.forEach(priceElem => {
                    const priceValue = parseFloat(priceElem.dataset.price);
                    const converted = priceValue * rate;
                    priceElem.textContent = formatPrice(converted, currency);
                });
            }

            updatePrices(currencySelector.value);

            currencySelector.addEventListener("change", function () {
                updatePrices(this.value);
            });

            checkboxes.forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    let totalPrice = productPrice;
                    checkboxes.forEach(function (checkbox) {
                        if (checkbox.checked) {
                            totalPrice += parseFloat(checkbox.getAttribute('data-price'));
                        }
                    });

                    totalPriceElem.dataset.price = totalPrice;

                    totalPriceElem.textContent = formatPrice(totalPrice * exchangeRates[currencySelector.value], currencySelector.value);
                });
            });
        });
    </script>
    <a style="position: absolute; left: 75%; top: 180px;" onclick="window.location.href='/admin/products/{{$product->uuId}}/edit'"
       class="inline-block mt-4 px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition">
        Edit Product
    </a>
    <div style="position: absolute; top: 12px; right:10px" class="w-48">
        <select id="currency-selector"
                class="mt-1 block w-1/2 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2">
            <option value="USD">USD</option>
            @if($rates['EUR']!=null)
                <option value="EUR">EUR</option>
            @endif
            @if($rates['PLN']!=null)
                <option value="PLN">PLN</option>
            @endif
        </select>
    </div>
    <div class="w-full px-6 bg-white space-y-6 ml-[300px]">
        <h2 class="text-4xl font-bold text-gray-900">{{ $product->name }}</h2>

        <p class="text-base text-gray-600">{{ $product -> type -> name  }}</p>

        <p class="text-lg text-gray-600">By {{ $product -> manufacturer-> name  }}</p>

        <div class="text-base text-gray-800 leading-relaxed">{{ $product->description }}</div>

        <p class="update-price text-3xl font-semibold text-green-700" data-price="{{ $product->price }}">${{ number_format($product->price, 2) }}</p>

        <p class="text-sm text-gray-500">Released: {{ date('d F Y', strtotime($product->release_date)) }}</p>
        <h3 class="text-2xl font-semibold mt-10">Services:</h3>
        <ul role="list" class="divide-y divide-gray-200">
            @foreach($product->services as $service)
                <div class="mt-4 w-[200px] border border-gray-200 rounded-xl p-4 shadow-sm bg-white">
                    <div class="space-y-2">
                        <span class="font-medium text-gray-900 text-lg">{{ $service->name }}</span>
                        <input type="checkbox" class="form-checkbox h-5 w-5 text-green-700" data-price="{{ $service->pivot->price }}">
                        <div class="font-semibold text-gray-900">Days needed: {{ number_format($service->pivot->days_needed) }}</div>
                        <div class="update-price font-semibold text-green-700" data-price="{{ $service->pivot->price }}">Price: ${{ number_format($service->pivot->price, 2) }}</div>
                    </div>
                </div>
            @endforeach
        </ul>

        <div style="position: absolute; left: 75%; top: 100px;" class="mt-6">
            <label class="block text-sm font-medium text-gray-700">Total Price:</label>
            <p id="totalPrice" data-price="{{ $product->price }}" value="${{number_format($product->price, 2)}}" class="update-price mt-1 px-3 py-2 border border-gray-300 rounded-md w-full bg-white text-gray-900">
        </div>
    </div>
</x-body>
