<x-layout>
    <div class="container mx-auto px-4 py-1">
        <h1 class="text-2xl font-bold mb-4">Google Books</h1>

        <form action="{{ route('googlebooks.search') }}" method="GET" class="flex space-x-2 mb-6">
            <select name="filter" id="filter"
                class="border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                <option value="title" {{ request('filter')=='title' ? 'selected' : '' }}>Title</option>
                <option value="author" {{ request('filter')=='author' ? 'selected' : '' }}>Author</option>
                <option value="publisher" {{ request('filter')=='publisher' ? 'selected' : '' }}>Publisher</option>
                <option value="isbn" {{ request('filter')=='isbn' ? 'selected' : '' }}>ISBN</option>
            </select>
            <input type="text" name="q" placeholder="Enter value..." value="{{ $query ?? '' }}"
                class="max-w-md border border-gray-300 rounded-lg px-3 py-2 ml-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition ml-2" type="submit">
                Search
            </button>
        </form>

        @if(!empty($books))
        <div class="overflow-x-auto">
            <table class="w-full border-2 border-gray-700 rounded-lg font-sans">

                <colgroup>
                    <col style="width: 10%;">
                    <col style="width: 10%;">
                    <col style="width: 10%;">
                    <col style="width: 10%;">
                    <col style="width: 10%;">
                    <col style="width: 10%;">
                    <col style="width: 20%;">
                    <col style="width: 10%;">
                </colgroup>

                <thead class="bg-gray-800 text-white uppercase tracking-wide text-sm font-semibold">
                    <tr>
                        <th class="px-4 py-3 text-left ">Title</th>
                        <th class="px-4 py-3 text-left ">Authors</th>
                        <th class="px-4 py-3 text-left ">Publisher</th>
                        <th class="px-4 py-3 text-left ">ISBN</th>
                        <th class="px-4 py-3 text-left ">Price</th>
                        <th class="px-4 py-3 text-center ">Cover</th>
                        <th class="px-4 py-3 text-left ">Description</th>
                        <th class="px-4 py-3 text-center ">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white text-gray-700 text-sm text-center">
                    @foreach($books as $book)
                    @php
                    $info = $book['volumeInfo'] ?? [];
                    @endphp
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-4 py-2 font-medium align-middle">{{ $info['title'] ?? 'No title' }}</td>
                        <td class="px-4 py-2 align-middle">{{ isset($info['authors']) ? implode(', ', $info['authors'])
                            : 'Unknown
                            author' }}</td>
                        <td class="px-4 py-2 align-middle">{{ $info['publisher'] ?? 'Unknown publisher' }}</td>
                        <td class="px-4 py-2 align-middle">{{ $info['industryIdentifiers'][0]['identifier'] ?? 'No ISBN'
                            }}</td>
                        <td class="px-4 py-2 align-middle">
                            {{ $book['saleInfo']['listPrice']['amount'] ?? 'No price' }}
                        </td>
                        <td class="px-4 py-2 align-middle">
                            @if(!empty($info['imageLinks']['thumbnail']))
                            <img src="{{ $info['imageLinks']['thumbnail'] }}" alt="cover"
                                class="w-16 h-20 object-cover rounded mx-auto">
                            @else
                            <span class="text-gray-500">No image</span>
                            @endif
                        </td>
                        <td class="px-4 py-2 max-w-lg align-top">
                            <div x-data="{ open: false }">
                                <template x-if="open">
                                    <div class="absolute bg-white dark:bg-gray-800 p-3 rounded shadow-md z-50 max-w-lg">
                                        {{ $info['description'] ?? 'No description available' }}
                                        <button x-on:click="open = false"
                                            class="text-blue-600 text-sm mt-2 hover:underline block">
                                            Read less
                                        </button>
                                    </div>
                                </template>

                                <template x-if="!open">
                                    <p class="overflow-hidden text-ellipsis whitespace-normal"
                                        style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                                        {{ $info['description'] ?? 'No description available' }}
                                    </p>
                                </template>

                                <button x-on:click="open = !open" class="text-blue-600 text-sm mt-1 hover:underline">
                                    <span x-text="open ? 'Read less' : 'Read more'"></span>
                                </button>
                            </div>
                        </td>
                        <td class="px-4 py-2 text-center">
                            <form action="{{ route('googlebooks.import') }}" method="POST">
                                @csrf
                                <input type="hidden" name="book[title]" value="{{ $info['title'] ?? '' }}">
                                <input type="hidden" name="book[isbn]"
                                    value="{{ $info['industryIdentifiers'][0]['identifier'] ?? '' }}">
                                @foreach($info['authors'] ?? [] as $author)
                                <input type="hidden" name="book[authors][]" value="{{ $author }}">
                                @endforeach
                                <input type="hidden" name="book[publisher]" value="{{ $info['publisher'] ?? '' }}">
                                <input type="hidden" name="book[description]" value="{{ $info['description'] ?? '' }}">
                                <input type="hidden" name="book[thumbnail]"
                                    value="{{ $info['imageLinks']['thumbnail'] ?? '' }}">
                                <button
                                    class="btn btn-success text-white px-3 py-1 rounded hover:bg-green-700 transition"
                                    type="submit">
                                    Import
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    <div class="mt-4">
        {{ $books->links() }}
    </div>
</x-layout>