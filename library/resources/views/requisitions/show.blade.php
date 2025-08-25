<x-layout>

    <x-slot name="heading">
        Details
    </x-slot>


    <div class="flex justify-center items-center py-3">
        <div class="bg-white shadow-lg rounded-lg w-full max-w-xl">
            <div class="text-black text-center py-3 px-6 rounded-t-lg">
                <h3 class="text-xl font-bold">Leave your review about the book</h3>
            </div>
            <div class="px-6 py-3">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="border border-gray-200 rounded p-4">
                        <p><strong>Book:</strong> {{ $requisition->book->name }}</p>
                        <p><strong>User:</strong> {{ $requisition->user->name }}</p>
                        <p><strong>Status:</strong>
                            @if($requisition->status === 'returned')
                            <span class="  bg-red-100 text-red-600">Returned</span>
                            @else
                            <span class="bg-green-100 text-green-600">Pending</span>
                            @endif
                        </p>
                        <p><strong>Return Date:</strong> {{ $requisition->actual_return_date ?? 'Not yet returned' }}
                        </p>

                        <!-- Review form: only shows if citizen and requisition is returned -->
                        @if(auth()->user()->isCitizen() && $requisition->status === 'returned')
                        <hr>
                        <form method="POST" action="{{ route('reviews.store', $requisition->book->id) }}">
                            @csrf
                            <div class="border border-gray-200 rounded p-4">
                                <textarea name="text" id="text" class="form-control w-full" rows="4"
                                    required></textarea>
                                <div class="mb-3">
                                    <label class="form-label">Avaliação:</label>
                                    <div id="star-rating">
                                        @for ($i = 1; $i <= 5; $i++) <span class="star" data-value="{{ $i }}"
                                            style="font-size:2rem; cursor:pointer;">&#9734;</span>
                                            @endfor
                                    </div>
                                    <input type="hidden" name="rating" id="rating" required>
                                </div>
                            </div>
                            <div class="flex justify-end space-x-4 mt-6">
                                <a href="{{ route('requisitions.index') }}"
                                    class="px-6 py-2 border border-gray-300 rounded-full text-black hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                    Cancel
                                </a>
                                <button type="submit" class="px-6 py-2 border border-gray-300 text-black rounded-full
                            hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">Submit
                                    Review</button>
                            </div>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const stars = document.querySelectorAll('#star-rating .star');
    const ratingInput = document.getElementById('rating');
    stars.forEach(star => {
        star.addEventListener('click', function() {
            const val = this.getAttribute('data-value');
            ratingInput.value = val;
            stars.forEach((s, i) => {
                s.innerHTML = i < val ? '&#9733;' : '&#9734;';
            });
        });
    });
    </script>
</x-layout>