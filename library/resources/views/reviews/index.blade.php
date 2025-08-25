<x-layout>
    <x-slot:heading>
        Reviews Management
    </x-slot:heading>

    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200">

        <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-900 text-white  tracking-wide text-sm font-semibold">
                <tr>
                    <th class="px-4 py-4 min-h-[2rem]">ID</th>
                    <th class="px-4 py-4 min-h-[2rem]">Book</th>
                    <th class="px-4 py-4 min-h-[2rem]">Citizen</th>
                    <th class="px-4 py-4 min-h-[2rem]">Status</th>
                    <th class="px-4 py-4 min-h-[2rem]">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reviews as $review)
                <tr class="hover:bg-gray-100 transition-colors duration-200 text-center">
                    <td class="px-4 py-2 ">{{ $review->id }}</td>
                    <td class="px-4 py-2">{{ $review->book->name }}</td>
                    <td class="px-4 py-2">{{ $review->user->name }}</td>
                    <td class="px-4 py-2">
                        <span class="
                                    @if($review->status === 'active')
                                        bg-green-100 text-green-800
                                    @elseif($review->status === 'pending')
                                        bg-gray-100 text-gray-800
                                    @else
                                        bg-red-100 text-red-700
                                    @endif
                                    px-2 py-1 rounded
                                ">
                            {{ ucfirst($review->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-2">
                        <a href="{{ route('reviews.show', $review->id) }}" class="btn btn-primary btn-sm">Details</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layout>