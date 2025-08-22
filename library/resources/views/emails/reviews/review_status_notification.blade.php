<h2>Review Status</h2>
<p><strong>Book:</strong> {{ $review->book->name }}</p>
<p><strong>Review Text:</strong> {{ $review->text }}</p>
@if($status === 'active')
<p>Your review has been approved and is now visible.</p>
@else
<p>Your review has been rejected.</p>
<p><strong>Justification:</strong> {{ $review->justification }}</p>
@endif

<div class="footer">
    <p>&copy; {{ date('Y') }} Library Management System. All rights reserved.</p>
</div>