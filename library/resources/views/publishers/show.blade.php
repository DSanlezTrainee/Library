<x-layout>
    <x-slot:heading>
        Publisher Details
    </x-slot:heading>

    <div class="mt-4 space-y-3">
        <div><strong>Name:</strong> {{ $publisher->name }}</div>

        <div><strong>Logo:</strong> <img src="{{ $publisher->logo }}" alt="No Logo"
                class="w-32 h-32 object-cover rounded"></div>

        <div><strong>Books:</strong>
            @if($publisher->books->count())
            <ul class="mt-2 space-y-2">
                @foreach($publisher->books as $book)
                <li>- {{ $book->name }}</li>
                @endforeach
            </ul>
            @else
            <div class="mt-2 text-gray-500">No Books</div>
            @endif
        </div>
    </div>
    </div>
</x-layout>