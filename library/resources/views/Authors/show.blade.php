<x-layout>
    <x-slot:heading>
        Author Details
    </x-slot:heading>

    <h2 class="text-lg font-bold">{{$author->name}}</h2>

   <div class="mt-4 space-y-3">
    <div><strong>Name:</strong> {{ $author->name }}</div>
    
    <div><strong>Photo:</strong> <img src="{{ $author->photo }}" alt="No Photo" class="w-32 h-32 object-cover rounded"></div>

    <div><strong>Biography:</strong> {{ $author->biography ?: 'No Biography' }}</div>

    <div>
        <strong>Books:</strong>
        @if($author->books->count())
            <ul class="mt-2 space-y-2">
                @foreach($author->books as $book)
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