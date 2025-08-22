<h2>New Review Submitted</h2>
<p><strong>Citizen:</strong> {{ $review->user->name }} ({{ $review->user->email }})</p>
<p><strong>Book:</strong> {{ $review->book->name }}</p>
<p><strong>Review Text:</strong> {{ $review->text }}</p>
<p><a href="{{ route('reviews.show', $review) }}" class="hover:underline">View review details</a></p>

<div class="footer">
    <p>&copy; {{ date('Y') }} Library Management System. All rights reserved.</p>
</div>