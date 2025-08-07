<x-body>
    <x-currency-converter :rates="$rates" />

    <div class="w-full px-6 bg-white space-y-6 ml-[300px]">
        <h2 class="text-4xl font-bold text-gray-900">{{ $product->name }}</h2>

        <p class="text-base text-gray-600">{{ $product->type->name }}</p>
        <p class="text-lg text-gray-600">By {{ $product->manufacturer->name }}</p>

        <div class="text-base text-gray-800 leading-relaxed">
            {{ $product->description }}
        </div>

        <p class="update-price text-3xl font-semibold text-green-700"
           data-base="{{ number_format(floatval(str_replace(',', '', explode(' ', $product->getPrice($rate, $product->price))[1])), 2, '.', '') }}">
            {{ $product->getPrice($rate, $product->price)}}
        </p>

        <p class="text-sm text-gray-500">
            Released: {{ \Carbon\Carbon::parse($product->release_date)->format('d F Y') }}
        </p>

        <h3 class="text-2xl font-semibold mt-10">Services:</h3>

        <ul role="list" class="divide-y divide-gray-200">
            @foreach($product->services as $service)
                @php
                    $servicePriceFormatted = $service->getPrice($rate, $service->pivot->price);
                @endphp

                <div class="mt-4 w-[200px] border border-gray-200 rounded-xl p-4 shadow-sm bg-white">
                    <div class="space-y-2">
                        <span class="font-medium text-gray-900 text-lg">{{ $service->name }}</span>

                        <input type="checkbox"
                               class="form-checkbox h-5 w-5 text-green-700"
                               data-price="{{ number_format(floatval(str_replace(',', '',explode(' ', $service->getPrice($rate, $service->pivot->price))[1])), 2, '.', '') }}">

                        <div class="font-semibold text-gray-900">
                            Days needed: {{ number_format($service->pivot->days_needed) }}
                        </div>

                        <div class="update-price font-semibold text-green-700">
                            Price: {{ $servicePriceFormatted }}
                        </div>
                    </div>
                </div>
            @endforeach
        </ul>

        <div style="position: absolute; left: 75%; top: 100px;" class="mt-6">
            <label class="block text-sm font-medium text-gray-700">Total Price:</label>
            <p id="totalPrice"
               data-total="{{ number_format(floatval(str_replace(',', '',explode(' ', $product->getPrice($rate, $product->price))[1])), 2, '.', '') }}"
               data-currency="{{ $rate }}"
               class="update-price mt-1 px-3 py-2 border border-gray-300 rounded-md w-full bg-white text-gray-900">
                {{ $product->getPrice($rate, $product->price) }}
            </p>
        </div>
    </div>
</x-body>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const totalPriceElem = document.getElementById("totalPrice");
        const checkboxes = document.querySelectorAll('input[type="checkbox"][data-price]');
        const basePrice = parseFloat(totalPriceElem.dataset.total);  // Преобразуем в число

        function formattedPrice(amount, currency) {
            switch (currency) {
                case 'USD':
                    return '$ ' + amount.toFixed(2);
                case 'PLN':
                    return 'PLN ' + amount.toFixed(2);
                case 'EUR':
                    return '€ ' + amount.toFixed(2);
                default:
                    return amount.toFixed(2) + ' ' + currency;
            }
        }


        function updateTotalPrice() {
            let total = basePrice;

            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    total += parseFloat(checkbox.dataset.price);  // Преобразуем в число
                }
            });

            totalPriceElem.textContent = formattedPrice(total, totalPriceElem.dataset.currency);
        }

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateTotalPrice);
        });
    });
</script>
