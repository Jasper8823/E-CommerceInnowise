<script src="https://cdn.tailwindcss.com"></script>

<div class="min-h-full">
    <nav class="bg-gray-800">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <div class="flex items-center">
                    <div class="shrink-0">
                        <img class="size-8" src="https://tailwindcss.com/plus-assets/img/logos/mark.svg?color=indigo&shade=500" alt="Your Company">
                    </div>
                    <div class="hidden md:block">
                        <div style="display:inline-block" class="ml-10 flex items-baseline space-x-4">
                            @php
                                $productLink = auth()->check() ? '/admin/products' : '/products';
                            @endphp
                            <x-nav-link href="{{ $productLink }}" :active="request()->is('products') || request()->is('admin/products')">Products</x-nav-link>
                        </div>
                        <div style="display:inline-block;position:absolute; right: 250px">
                            @guest()
                                <x-nav-link style="margin: 5px" href="/login">Log In</x-nav-link>
                                <x-nav-link style="margin: 5px" href="/register">Register</x-nav-link>
                            @endguest
                            @auth()
                                <div style="position: relative; top: -13px;">
                                    <form method="POST" action="{{ route('logout') }}" style="display: inline">
                                        @csrf
                                        <button type="submit" class="text-white hover:bg-gray-700 px-3 py-2 rounded-md text-base font-medium" style="margin: 5px">
                                            Log Out
                                        </button>
                                    </form>
                                    <x-nav-link style="margin: 5px" href="/admin/products/create">Create Product</x-nav-link>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <header class="bg-white shadow-sm">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        </div>
    </header>
    <main>
        {{$slot}}
    </main>
</div>
