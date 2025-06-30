<x-body>
    <div class="w-full px-6 bg-white space-y-6 ml-[300px]">
        <h2 class="text-4xl font-bold text-gray-900">{{ $product->name }}</h2>

        <p class="text-base text-gray-600">{{ $product -> type -> name  }}</p>

        <p class="text-lg text-gray-600">By {{ $product -> manufacturer-> name  }}</p>

        <div class="text-base text-gray-800 leading-relaxed">{{ $product->description }}</div>

        <p class="text-3xl font-semibold text-green-700">${{ number_format($product->price, 2) }}</p>

        <p class="text-sm text-gray-500">Released: {{ date('d F Y', strtotime($product->releaseDate)) }}</p>
        <h3 class="text-2xl font-semibold mt-10">Services:</h3>
        <ul role="list" class="divide-y divide-gray-200">
            @foreach($product->services as $service)
                <div class="mt-4 w-[200px] border border-gray-200 rounded-xl p-4 shadow-sm bg-white">
                    <div class="space-y-2">
                        <span class="font-medium text-gray-900 text-lg">{{ $service->name }}</span>
                        <input type="checkbox" class="form-checkbox h-5 w-5 text-green-700" data-price="{{ $service->pivot->price }}">
                        <div class="font-semibold text-gray-900">Days needed: {{ number_format($service->pivot->daysNeeded) }}</div>
                        <div class="font-semibold text-green-700">Price: ${{ number_format($service->pivot->price, 2) }}</div>
                    </div>
                </div>
            @endforeach
        </ul>

        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700">Total Price:</label>
            <input type="text" id="totalPrice" value="${{number_format($product->price, 2)}}" readonly class="mt-1 px-3 py-2 border border-gray-300 rounded-md w-full bg-white text-gray-900">
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            const totalPriceInput = document.getElementById('totalPrice');
            const productPrice = {{ $product->price }};

            checkboxes.forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    let totalPrice = productPrice;
                    checkboxes.forEach(function (checkbox) {
                        if (checkbox.checked) {
                            totalPrice += parseFloat(checkbox.getAttribute('data-price'));
                        }
                    });
                    totalPriceInput.value = totalPrice.toFixed(2);
                });
            });
        });
    </script>
</x-body>

