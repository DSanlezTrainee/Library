<div>
    <input type="text" wire:model.live="search" placeholder="Search publishers..."
        class="input input-primary max-w-md mb-4" />

    <div class="overflow-x-auto">
        <table class="table-auto w-full border-collapse border border-gray-300" style="table-layout: fixed;">
            <thead>
                <tr>
                    <th class="border border-gray-300 px-4 py-2 w-[15%]">
                        <button wire:click="sortBy('name')"
                            class="flex items-center justify-between w-full hover:bg-gray-100 px-2 py-1 rounded min-h-[2rem]">
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
                    <th class="border border-gray-300 px-4 py-2 w-[20%]">Logo</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($publishers as $publisher)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="border border-gray-300 px-4 py-2">{{ $publisher->name }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center">
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