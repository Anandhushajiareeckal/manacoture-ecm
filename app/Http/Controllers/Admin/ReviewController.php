<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\ActivityLog;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['product', 'customer', 'moderator']);

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($rating = $request->input('rating')) {
            $query->where('rating', $rating);
        }

        $reviews = $query->latest()->paginate(15)->withQueryString();

        $allCount = Review::count();
        $pendingCount = Review::pending()->count();
        $approvedCount = Review::approved()->count();
        $avgRating = round(Review::approved()->avg('rating') ?: 0, 1);

        return view('admin.reviews.index', compact('reviews', 'allCount', 'pendingCount', 'approvedCount', 'avgRating'));
    }

    public function updateStatus(Request $request, Review $review)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $review->update([
            'status' => $validated['status'],
            'moderated_by' => auth()->id(),
            'moderated_at' => now(),
        ]);

        ActivityLog::log('review.moderate', "Moderated review #{$review->id} as {$validated['status']}", $review);

        return back()->with('success', "Review #{$review->id} marked as {$validated['status']}.");
    }

    public function destroy(Review $review)
    {
        $review->delete();
        ActivityLog::log('review.delete', "Deleted review #{$review->id}", $review);

        return back()->with('success', 'Review removed.');
    }
}
