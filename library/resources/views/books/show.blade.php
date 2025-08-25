<x-layout>
    <x-slot:heading>
        Book Details
    </x-slot:heading>

    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <div class="flex items-center mb-2">
        </div>
        <h2 class="text-lg font-bold mb-3">{{ $book->name }}</h2>

        <td class="border border-gray-300 px-4 py-2 flex justify-center">
            <img src="{{ $book->cover_image }}" alt="Cover Image" width=100 height=100>
        </td>
        <div class="mb-4 mt-2">
            @if($reviewsCount > 0)
            <strong>Rating:</strong> {{ number_format($averageRating, 1) }} <span class="star selected">★</span> ({{
            $reviewsCount }} reviews)</span>
            @else
            <p>No reviews yet.</p>
            @endif
        </div>
        <div class="mt-4">
            <strong>Bibliography:</strong> {{ $book->bibliography ?? 'No Bibliography' }}
        </div>
    </div>

    @php
    $tab = request('tab', 'history');
    @endphp
    {{-- Tabs --}}
    <div x-data="{ tab: '{{ $tab }}' }" class="mb-6">
        <div class="border-b border-gray-200 mb-4">
            <nav class="-mb-px flex space-x-4">
                <button @click="tab = 'history'"
                    :class="tab === 'history' ? 'border-gray-500 text-black font-bold border-b-2' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-2 px-1 text-sm">
                    Request History
                </button>
                <button @click="tab = 'reviews'"
                    :class="tab === 'reviews' ? 'border-gray-500 text-black font-bold border-b-2' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="whitespace-nowrap py-2 px-1 text-sm">
                    Reviews
                </button>
            </nav>
        </div>

        <div class="mt-6">
            <div x-show="tab === 'reviews'">
                <h3 class="text-md font-semibold mb-2">Reviews</h3>
                @if($activeReviews->isNotEmpty())
                <ul class="mb-4">
                    @foreach($activeReviews as $review)
                    <li class="border rounded p-3 mb-2 flex flex-col gap-1">
                        <div>
                            <strong>{{ $review->user->name }}</strong>
                        </div>
                        <div class="mb-1">
                            <span class="text-lg">
                                @for ($i = 1; $i <= 5; $i++) @if ($i <=$review->rating)
                                    <span class="star selected">★</span>
                                    @else
                                    <span class="star">☆</span>
                                    @endif
                                    @endfor
                            </span>
                        </div>
                        <span class="text-gray-700">{{ $review->text }}</span>
                    </li>
                    @endforeach
                </ul>

                <div class="mt-4">
                    {{ $activeReviews->appends(['tab' => 'reviews'])->links() }}
                </div>
                @else
                <p>No active reviews for this book.</p>
                @endif
            </div>
            <hr>

            {{-- History Tab --}}
            <div x-show="tab === 'history'">
                <h3 class="text-md font-semibold mb-2">Requests History</h3>
                @if($book->requisitions && $book->requisitions->isNotEmpty())
                <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200">
                    <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-900 text-white tracking-wide text-sm font-semibold">
                            <tr>
                                <th class="px-4 py-4 min-h-[2rem]">User</th>
                                <th class=" px-4 py-4 min-h-[2rem]">Requested At</th>
                                <th class=" px-4 py-4 min-h-[2rem]">Returned At</th>
                                <th class=" px-4 py-4 min-h-[2rem]">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @foreach($book->requisitions as $requisition)
                            <tr>
                                <td class=" px-4 py-2">{{ $requisition->user->name ?? 'N/A' }}</td>
                                <td class=" px-4 py-2">{{ $requisition->created_at->format('d/m/Y') }}</td>
                                <td class=" px-4 py-2">{{ $requisition->actual_return_date ?
                                    \Carbon\Carbon::parse($requisition->actual_return_date)->format('d/m/Y') : '-' }}
                                </td>
                                <td class=" px-4 py-2 ">
                                    @if($requisition->actual_return_date)
                                    <span class="bg-red-100 text-red-600">Returned</span>
                                    @else
                                    <span class="bg-green-100 text-green-600 font-bold">Active</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $requisitions->appends(['tab' => 'history'])->links() }}
                    </div>
                    @else
                    <p>No requisitions found for this book.</p>
                    @endif
                </div>
            </div>
        </div>

</x-layout>