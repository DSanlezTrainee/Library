<x-layout>
    <x-slot name="heading">
        Update Review
    </x-slot>

    <div class="flex justify-center items-center py-3">
        <div class="bg-white shadow-lg rounded-lg w-full max-w-xl">
            <div class="text-black text-center py-3 px-6 rounded-t-lg">
                <h3 class="text-xl font-bold">Review Details</h3>
            </div>
            <div class="px-6 py-3">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="border border-gray-200 rounded p-4">
                        <p><span class="font-medium"><strong>Book:</strong></span> {{ $review->book->name }}</p>
                        <p><span class="font-medium"><strong>Citizen:</strong></span> {{ $review->user->name }}</p>
                        <p><span class="font-medium"><strong>Text:</strong></span> {{ $review->text }}</p>
                        <p>
                            <span class="font-medium"><strong>Current Status:</strong></span>
                            <span class="inline-block py-1 px-2 rounded
                                @if($review->status === 'active')
                                    bg-green-100 text-green-800
                                @elseif($review->status === 'pending')
                                    bg-gray-100 text-gray-800
                                @else
                                    bg-red-100 text-red-700
                                @endif
                                ">
                                {{ ucfirst($review->status) }}
                            </span>
                        </p>
                    </div>
                    <div class="border border-gray-200 rounded p-4 flex items-center">
                        @if($review->status === 'rejected')
                        <p><span class="font-medium"><strong>Justification:</strong></span> {{ $review->justification }}
                        </p>
                        @endif
                    </div>
                    <hr>
                    <form method="POST" action="{{ route('reviews.update', $review->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="border border-gray-200 rounded p-4">
                            <label for="status" class="form-label">Change Status</label>
                            <select name="status" id="status" class="form-control mb-3">
                                <option value="active" @if($review->status=='active') selected @endif>Approve</option>
                                <option value="rejected" @if($review->status=='rejected') selected @endif>Reject
                                </option>
                            </select>

                            <div class="mb-3" id="justification-box" style="display: none;">
                                <label for="justification" class="form-label mb-2" style="display:block;">Justification
                                    (required if
                                    rejected)</label>
                                <textarea name="justification" id="justification" class="form-control w-full"
                                    rows="3">{{ $review->justification }}</textarea>
                            </div>
                        </div>
                        <div class="flex justify-end space-x-4 mt-6">

                            <a href="{{ route('reviews.index') }}"
                                class="px-6 py-2 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                Cancel
                            </a>
                            <button type="submit" class="px-6 py-2 border border-gray-300 text-black rounded-full
                            hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>

    <script>
        document.getElementById('status').addEventListener('change', function() {
        document.getElementById('justification-box').style.display = this.value === 'rejected' ? 'block' : 'none';
    });
    window.onload = function() {
        if(document.getElementById('status').value === 'rejected') {
            document.getElementById('justification-box').style.display = 'block';
        }
    }
    </script>
</x-layout>