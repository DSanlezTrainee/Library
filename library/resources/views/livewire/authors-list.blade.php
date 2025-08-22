<div>
    <input type="text" wire:model.live="search" placeholder="Search authors..."
        class="max-w-md border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none mb-4" />

    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200">
        <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-900 text-white  tracking-wide text-sm font-semibold text-center">
                <tr>
                    <th class="px-4 py-2 w-[15%]">
                        <button wire:click="sortBy('name')"
                            class="flex items-center justify-center w-full hover:bg-gray-700 px-2 py-1 rounded min-h-[2rem] transition-colors duration-200">
                            <span>Name</span>
                            <span class="ml-2 text-xs">
                                @if($sortField === 'name')
                                @if($sortDirection === 'asc')
                                ↑
                                @else
                                ↓
                                @endif
                                @else
                                ↕
                                @endif
                            </span>
                        </button>
                    </th>
                    <th class="border border-gray-300 px-4 py-2 w-[20%]">Photo</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($authors as $author)
                <tr class="hover:bg-gray-50 transition-colors duration-200 text-center">
                    <td class="border border-gray-300 px-4 py-2">{{ $author->name }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center align-middle">
                        <img src="{{ $author->photo }}" alt="Author Photo"
                            class="w-24 h-24 object-cover rounded mx-auto">
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="text-center py-4">No authors found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $authors->links() }}
    </div>
</div>