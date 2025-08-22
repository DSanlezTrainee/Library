<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReviewStatusNotification;
use App\Mail\NewReviewAdminNotification;

class ReviewController extends Controller
{

    // Listagem de reviews para admin
    public function index()
    {
        $reviews = Review::with('user', 'book')->orderByRaw("status = 'active' desc")->orderByDesc('created_at')->paginate(10);
        return view('reviews.index', compact('reviews'));
    }

    // Detalhe do review para admin
    public function show(Review $review)
    {
        $review->load('user', 'book');

        return view('reviews.show', compact('review'));
    }

    public function store(Request $request, $bookId)
    {
        $request->validate([
            'text' => 'required|string|max:1000',
        ]);

        $review = Review::create([
            'user_id' => Auth::id(),
            'book_id' => $bookId,
            'text' => $request->text,
            'status' => 'pending',
        ]);

        // Notificar admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new NewReviewAdminNotification($review));
        }

        return redirect()->route('requisitions.index')->with('success', 'Review submitted and awaits approval.');
    }



    // Update review state
    public function update(Request $request, Review $review)
    {
        $request->validate([
            'status' => 'required|in:active,rejected',
            'justification' => 'required_if:status,rejected',
        ]);
        $review->status = $request->status;
        $review->justification = $request->status === 'rejected' ? $request->justification : null;
        $review->save();

        // Notify user
        $user = $review->user;
        if ($review->status === 'active') {
            Mail::to($user->email)->send(new ReviewStatusNotification($review, 'active'));
        } else {
            Mail::to($user->email)->send(new ReviewStatusNotification($review, 'rejected'));
        }

        return redirect()->route('reviews.index')->with('success', 'Review updated with success.');
    }
}
