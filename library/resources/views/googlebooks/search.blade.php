<x-layout>
    <div class="container">
        <h1>Search Google Books</h1>

        <form action="{{ route('googlebooks.search') }}" method="GET">
            <input type="text" name="q" placeholder="Search for books..." value="{{ $query ?? '' }}">
            <button class="btn btn-primary" type="submit">Search</button>
        </form>

        @if(!empty($books))
        <h2>Results</h2>
        <ul>
            @foreach($books as $book)
            @php
            $info = $book['volumeInfo'] ?? [];
            @endphp
            <li style="margin-bottom: 20px;">
                <strong>Title : {{ $info['title'] ?? 'No title' }}</strong><br>
                Autor: {{ isset($info['authors']) ? implode(', ', $info['authors']) : 'Unknown author' }} <br>
                <img src="{{ $info['imageLinks']['thumbnail'] ?? '' }}" alt="cover"><br>
                <p>{{ $info['description'] ?? 'No description available' }}</p>
                <p>Publisher: {{ $info['publisher'] ?? 'Unknown publisher' }}</p>
                <p>ISBN: {{ $info['industryIdentifiers'][0]['identifier'] ?? 'No ISBN' }}</p>

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
                    <input type="hidden" name="book[thumbnail]" value="{{ $info['imageLinks']['thumbnail'] ?? '' }}">
                    <button class ="btn btn-success" type="submit">Import</button>
                </form>
            </li>
            @endforeach
        </ul>
        @endif
    </div>
</x-layout>