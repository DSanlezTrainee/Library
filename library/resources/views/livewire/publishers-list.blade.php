<div>
    <input type="text" wire:model.live="search" placeholder="Search publishers..."
        class="input input-primary max-w-md mb-4" />

    <table class="table-auto w-full border-collapse border border-gray-300">
        <thead>
            <tr>
                <th class="border border-gray-300 px-4 py-2 w-3/5">Name</th>
                <th class="border border-gray-300 px-4 py-2 w-2/5">Logo</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($publishers as $publisher)
            <tr class="hover:bg-gray-50 transition-colors duration-200">
                <td class="border border-gray-300 px-4 py-2">{{ $publisher->name }}</td>
                <td class="border border-gray-300 px-4 py-2 text-center">
                    <img src="{{ $publisher->logo }}" alt="Publisher Logo" class="w-24 h-24 object-cover rounded mx-auto">
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="2" class="text-center py-4">No publishers found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $publishers->links() }}
    </div>
</div>