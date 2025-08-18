<div>
    <input type="text" wire:model.live="search" placeholder="Search publishers..."
        class="input input-primary max-w-md mb-4" />

    <div class="overflow-x-auto">
        <table class="table-auto w-full border-2 border-gray-700 rounded-lg font-sans" style="table-layout: fixed;">
            <thead class="bg-gray-800 text-white text-left uppercase tracking-wide text-sm font-semibold">
                <tr>
                    <th class="px-4 py-3 w-[15%]">
                        <button wire:click="sortBy('name')"
                            class="flex items-center justify-justify-start w-full hover:bg-gray-700/20 px-2 py-1 rounded min-h-[2rem] transition-colors duration-200">
                            <span>Name</span>
                            <span class="ml-3 text-xs">
                                @if($sortField === 'name')
                                @if($sortDirection === 'asc') ↑ @else ↓ @endif
                                @else ↕ @endif
                            </span>
                        </button>
                    </th>
                    <th class="px-4 py-3 w-[20%]">Logo</th>
                </tr>
            </thead>
            <tbody class="bg-white text-gray-700 text-sm">
                @forelse ($publishers as $publisher)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="px-4 py-2 border-b border-gray-300">{{ $publisher->name }}</td>
                    <td class="px-4 py-2 text-center border-b border-gray-300">
                        <img src="{{ $publisher->logo }}" alt="Publisher Logo"
                            class="w-24 h-24 object-cover rounded mx-auto">
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" class="text-center py-4">No publishers found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $publishers->links() }}
    </div>
</div>