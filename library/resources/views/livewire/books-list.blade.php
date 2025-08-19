<div>
    @if (session()->has('success'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
        class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 relative">
        <div class="flex">
            <div class="py-1">
                <svg class="h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <div>
                <p class="font-bold">Success!</p>
                <p>{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="absolute top-0 right-0 mt-4 mr-4 text-green-500 hover:text-green-700">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
    </div>
    @endif

    @if (session()->has('error'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
        class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 relative">
        <div class="flex">
            <div class="py-1">
                <svg class="h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="font-bold">Error!</p>
                <p>{{ session('error') }}</p>
            </div>
            <button @click="show = false" class="absolute top-0 right-0 mt-4 mr-4 text-red-500 hover:text-red-700">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
    </div>
    @endif

    <div class="flex justify-between items-center mb-4">
        <div class="flex gap-2 items-center">

            <div x-data="{ open: false }" class="relative" id="filterDropdown">
                <div @click="open = !open" role="button" class="btn m-1 min-w-[100px]">
                    {{ ucfirst($searchFieldLabel ?? 'Filters') }}
                </div>
                <ul x-show="open" @click.away="open = false"
                    class="absolute left-0 mt-1 bg-white dark:bg-gray-800 border border-gray-200 rounded shadow-lg z-50 w-52 py-1">
                    <li><a class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700"
                            wire:click.prevent="$set('searchField', 'all'); open = false">All</a></li>
                    <li><a class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700"
                            wire:click.prevent="$set('searchField', 'name'); open = false">Book Name</a></li>
                    <li><a class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700"
                            wire:click.prevent="$set('searchField', 'publisher'); open = false">Publisher</a></li>
                    <li><a class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700"
                            wire:click.prevent="$set('searchField', 'author'); open = false">Author</a></li>
                    <li><a class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700"
                            wire:click.prevent="$set('searchField', 'isbn'); open = false">ISBN</a></li>
                </ul>
            </div>

            <input type="text" wire:model.live="search" placeholder="Search books..."
                class="max-w-md border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" />
        </div>
        @if(auth()->user() && auth()->user()->isAdmin())
        <a href="{{ route('books.create') }}" class="btn btn-primary">
            Add Book
        </a>
        @endif
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

    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <colgroup>
                <col style="width: 20%;">
                <col style="width: 14%;">
                <col style="width: 15%;">
                <col style="width: 10%;">
                <col style="width: 12%;">
                <col style="width: 11%;">
                <col style="width: 13%;">
                <col style="width: 10%;">
            </colgroup>
            <thead class="bg-gray-800 text-white  tracking-wide text-sm font-semibold">
                <tr>
                    <th class="px-4 py-2">
                        <button wire:click="sortBy('name')"
                            class="flex items-center justify-between w-full hover:bg-gray-700 px-2 py-1 rounded min-h-[2rem]">
                            <span class="flex items-center gap-3">
                                Name
                                <span class=" text-xs">
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
                            </span>
                        </button>
                    </th>
                    <th class="px-4 py-2">
                        <button wire:click="sortBy('author')"
                            class="flex items-center justify-between w-full hover:bg-gray-700 px-2 py-1 rounded min-h-[2rem]">
                            <span class="flex items-center gap-3">
                                Author
                                <span class="ml-2 text-xs">
                                    @if($sortField === 'author')
                                    @if($sortDirection === 'asc')
                                    ↑
                                    @else
                                    ↓
                                    @endif
                                    @else
                                    ↕
                                    @endif
                                </span>
                            </span>
                        </button>
                    </th>
                    <th class="px-4 py-2">
                        <button wire:click="sortBy('publisher')"
                            class="flex items-center justify-between w-full hover:bg-gray-700 px-2 py-1 rounded min-h-[2rem]">
                            <span class="flex items-center gap-3">
                                Publisher

                                <span class="ml-2 text-xs">
                                    @if($sortField === 'publisher')
                                    @if($sortDirection === 'asc')
                                    ↑
                                    @else
                                    ↓
                                    @endif
                                    @else
                                    ↕
                                    @endif
                                </span>
                            </span>
                        </button>
                    </th>
                    <th class="px-4 py-2">
                        <button wire:click="sortBy('price')"
                            class="flex items-center justify-between w-full hover:bg-gray-700 px-2 py-1 rounded min-h-[2rem]">
                            <span class="flex items-center gap-3">
                                Price

                                <span class="ml-2 text-xs">
                                    @if($sortField === 'price')
                                    @if($sortDirection === 'asc')
                                    ↑
                                    @else
                                    ↓
                                    @endif
                                    @else
                                    ↕
                                    @endif
                                </span>
                            </span>
                        </button>
                    </th>
                    <th class="px-4 py-2">
                        <button wire:click="sortBy('isbn')"
                            class="flex items-center justify-between w-full hover:bg-gray-700 px-2 py-1 rounded min-h-[2rem]">
                            <span class="flex items-center gap-3">
                                ISBN

                                <span class="ml-2 text-xs">
                                    @if($sortField === 'isbn')
                                    @if($sortDirection === 'asc')
                                    ↑
                                    @else
                                    ↓
                                    @endif
                                    @else
                                    ↕
                                    @endif
                                </span>
                            </span>
                        </button>
                    </th>
                    <th class=" px-4 py-2 font-bold tracking-wide text-sm">Cover Image</th>
                    <th class=" px-4 py-2 font-bold tracking-wide text-sm">Details</th>
                    <th class=" px-4 py-2 font-bold tracking-wide text-sm">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($books as $book)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class=" px-4 py-2">{{ $book->name }}</td>
                    <td class=" px-4 py-2">{{ $book->authors->pluck('name')->join(', ') ?: 'No
                        Authors' }}</td>
                    <td class=" px-4 py-2">{{ $book->publisher->name }}</td>
                    <td class=" px-4 py-2">
                        ${{ number_format($book->formatted_price, 2) }}
                    </td>
                    <td class=" px-4 py-2">{{ $book->isbn }}</td>
                    <td class=" px-4 py-2 flex justify-center">
                        <img src="{{ $book->cover_image }}" alt="Cover Image" class="w-16 h-20 object-cover rounded">
                    </td>
                    <td class=" px-4 py-2 text-center">
                        <a href="/books/{{ $book->id }}" class="text-blue-500 text-align:center hover:underline">View
                            Details</a>
                    </td>

                    <td class=" px-4 py-2 text-center">

                        @if(auth()->user() && auth()->user()->isAdmin())
                        <!-- Botões de admin -->
                        <a href="/books/{{ $book->id }}/edit" class="text-blue-500 hover:underline">
                            <img src="{{ asset('images/edit.png') }}" alt="Edit" class="w-5 h-5 inline mb-2">
                        </a>
                        <button type="button" class="text-red-500 hover:underline delete-book-btn"
                            data-book-id="{{ $book->id }}">
                            <img src="{{ asset('images/remove.png') }}" alt="Delete" class="w-5 h-5 inline">
                        </button>
                        @endif

                        <!-- Botão de requisição para usuários normais -->
                        @if(!$book->requisitions->where('status', 'active')->count() && $userActiveRequisitionsCount <
                            3) <a href="{{ route('requisitions.create', ['book_id' => $book->id]) }}"
                            class="bg-blue-600 text-white px-1 py-1 rounded hover:bg-blue-700 inline-block">
                            Requisition
                            </a>
                            @else
                            <div>
                                <span class="text-gray-400">Not available</span>
                                @endif
                            </div>

                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <div>
                            <div>We don't have your book?</div>
                            <div class="mt-2 flex justify-center">
                                <a href="{{ route('googlebooks.search') }}" class="btn btn-primary">Try here</a>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $books->links('pagination::tailwind') }}
    </div>


    <!-- Dropdown functionality is now handled by Alpine.js -->
    <!-- Replace your existing modal implementation with this: -->
    <div id="deleteConfirmationModal" class="fixed inset-0 z-50 flex items-center justify-center hidden">
        <div class="fixed inset-0 bg-black opacity-50" id="modalBackdrop"></div>
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 z-10 w-full max-w-md mx-4">
            <h3 class="font-bold text-lg mb-4">Confirm</h3>
            <p class="py-4">Do you really want to delete this book?</p>
            <div class="flex justify-end gap-3 mt-4">
                <button id="btnCancel" type="button"
                    class="bg-red-600 hover:bg-red-700 text-white  py-2 px-4 rounded">No</button>
                <form id="delete-form" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-success">Yes</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Função para abrir modal
    function openModal() {
        const modal = document.getElementById('deleteConfirmationModal');
        const body = document.querySelector('body');
        modal.classList.remove('hidden');
        body.classList.add('overflow-hidden'); // Prevents scrolling when modal is open
    }

    // Função para fechar modal
    function closeModal() {
        const modal = document.getElementById('deleteConfirmationModal');
        const body = document.querySelector('body');
        modal.classList.add('hidden');
        body.classList.remove('overflow-hidden');
    }

    // Fechar modal ao clicar no backdrop
    document.getElementById('modalBackdrop').addEventListener('click', () => {
        closeModal();
    });

    // Fechar modal ao clicar em "No"
    document.getElementById('btnCancel').addEventListener('click', () => {
        closeModal();
    });

    // Configura botões de delete para abrir modal e definir action
    document.querySelectorAll('.delete-book-btn').forEach(button => {
        button.addEventListener('click', event => {
            event.preventDefault();
            const bookId = button.dataset.bookId;
            const form = document.getElementById('delete-form');
            form.action = `/books/${bookId}`;
            openModal();
        });
    });
    </script>

</div>