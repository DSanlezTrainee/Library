<x-layout>
    <x-slot:heading>
        Book Details
    </x-slot:heading>

    <h2 class="text-lg font-bold">{{$book->name}}</h2>


    <div class="mt-4">

        <strong>Bibliography:</strong> {{ $book->bibliography ? $book->bibliography : 'No Bibliography' }}

</x-layout>