<div>
    <div class="flex justify-between items-center mb-4">
        <input type="text" wire:model.live="search" placeholder="Search books..."
            class="input input-primary max-w-md" />

        <button wire:click="export"
            class="btn btn-success flex items-center gap-2 hover:bg-green-600 transition-colors duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Export to Excel
        </button>
    </div>

    <table class="table-auto w-full border-collapse border border-gray-300">
        <thead>
            <tr>
                <th class="border border-gray-300 px-4 py-2 w-1/5">Name</th>
                <th class="border border-gray-300 px-4 py-2 w-1/6">Author</th>
                <th class="border border-gray-300 px-4 py-2 w-1/8">Publisher</th>
                <th class="border border-gray-300 px-4 py-2 w-1/12">Price</th>
                <th class="border border-gray-300 px-4 py-2 w-1/8">ISBN</th>
                <th class="border border-gray-300 px-4 py-2 w-1/8">Cover Image</th>
                <th class="border border-gray-300 px-4 py-2 w-1/8">Bibliography</th>

            </tr>
        </thead>
        <tbody>
            @forelse ($books as $book)
            <tr class="hover:bg-gray-50 transition-colors duration-200">
                <td class="border border-gray-300 px-4 py-2">{{ $book->name }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $book->authors->pluck('name')->join(', ') ?: 'No
                    Authors' }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $book->publisher->name }}</td>
                <td class="border border-gray-300 px-4 py-2">
                    ${{ number_format($book->formatted_price, 2) }}
                </td>
                <td class="border border-gray-300 px-4 py-2">{{ $book->isbn }}</td>
                <td class="border border-gray-300 px-4 py-2">
                    <img src="{{ $book->cover_image }}" alt="Cover Image" class="w-16 h-20 object-cover rounded">
                </td>
                <td class="border border-gray-300 px-4 py-2 text-center">
                    <a href="/books/{{ $book->id }}" class="text-blue-500 text-align:center hover:underline">View
                        Bibliography</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center py-4">No books found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $books->links() }}
    </div>
</div>