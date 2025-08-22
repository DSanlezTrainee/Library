<x-layout>

    <div class="container">
        <h1>Details</h1>
        <!-- Details -->
        <p><strong>Book:</strong> {{ $requisition->book->name }}</p>
        <p><strong>User:</strong> {{ $requisition->user->name }}</p>
        <p><strong>Status:</strong> {{ $requisition->status }}</p>
        <p><strong>Return Date:</strong> {{ $requisition->actual_return_date ?? 'Not yet returned' }}</p>

        <!-- Review form: only shows if citizen and requisition is returned -->
        @if(auth()->user()->isCitizen() && $requisition->status === 'returned')
        <hr>
        <h3>Leave your review about the book</h3>
        <form method="POST" action="{{ route('reviews.store', $requisition->book->id) }}">
            @csrf
            <div class="mb-3">
                <label for="text" class="form-label">Review</label>
                <textarea name="text" id="text" class="form-control" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Submit Review</button>
        </form>
        @endif
    </div>
</x-layout>