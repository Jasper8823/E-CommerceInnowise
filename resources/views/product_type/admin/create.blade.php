<x-body>
    <form method="POST" action="{{ route('admin.product_types.store') }}">
        @csrf
        <div class="space-y-6 pr-40 pl-40">
            <div>
                <label for="name" class="block text-xl font-medium text-gray-700">Product Type Name</label>
                <input type="text" name="name" id="name" required
                       class="p-4 mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm text-xl">
            </div>

            <div class="flex justify-end pt-4">
                <button type="submit"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-base font-semibold text-white hover:bg-indigo-500">
                    Create
                </button>
            </div>
        </div>
    </form>
</x-body>
