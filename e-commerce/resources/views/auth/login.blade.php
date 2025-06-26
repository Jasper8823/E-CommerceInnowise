<x-body>
    <form method="POST" action="/login" class="max-w-3xl mx-auto bg-white p-8 rounded-xl shadow">
        @csrf
        <h2 class="text-xl font-semibold text-gray-900 mb-8">Login</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
            <div class="col-span-1 sm:col-span-2">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input
                    type="email"
                    name="email"
                    id="email"
                    required
                    class="w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm px-4 py-2"
                >
            </div>
            <div class="col-span-1 sm:col-span-2">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input
                    type="password"
                    name="password"
                    id="password"
                    required
                    class="w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm px-4 py-2"
                >
            </div>
        </div>
        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-md p-4 text-sm">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="flex justify-end space-x-4">
            <button
                type="button"
                onclick="history.back()"
                class="px-4 py-2 text-sm font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md"
            >
                Cancel
            </button>
            <button type="submit" class="px-4 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 rounded-md shadow">
                Login
            </button>
        </div>
    </form>
</x-body>
