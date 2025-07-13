@props(['rates' => []])

<form method="GET" action="{{ url()->current() }}">
    <div style="position: absolute; top: 12px; right:10px" class="w-48 z-50">
        <select name="currency-selector" id="currency-selector"
                class="mt-1 block w-1/2 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm p-2"
                onchange="this.form.submit()">
            @if(isset($rates['USD'])) <option value="USD" {{ request('currency-selector') === 'USD' ? 'selected' : '' }}>USD</option> @endif
            @if(isset($rates['EUR'])) <option value="EUR" {{ request('currency-selector') === 'EUR' ? 'selected' : '' }}>EUR</option> @endif
            @if(isset($rates['PLN'])) <option value="PLN" {{ request('currency-selector') === 'PLN' ? 'selected' : '' }}>PLN</option> @endif
        </select>
    </div>
</form>
