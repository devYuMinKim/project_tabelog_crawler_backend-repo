<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Models\Review;

// Hyn2's part
class ReviewController extends Controller
{
    public function create(CreateReviewRequest $request)
    {
        $validated = $request->validated();

        $review = new Review([
            'author_id' => $validated['author_id'],
            'restaurant_id' => $validated['restaurant_id'],
            'rating' => $validated['rating'],
            'review_text' => $validated['review_text'],
            'image_file' => $validated['image_file']
        ]);

        $review->save();

        return response()->json([
            'message' => '리뷰가 성공적으로 작성되었습니다',
            'data' => $review
        ], 200);
    }

    public function update(UpdateReviewRequest $request, Review $review)
    {
        $validated = $request->validated();

        // 인증된 사용자의 id와 리뷰 작성자의 id가 일치하는지 확인
        if ($request->user()->id !== $review->author_id) {
            return response()->json([
                'message' => '실행 권한이 없습니다'
            ], 403);
        }

        $review->update([
            'rating' => $validated['rating'],
            'review_text' => $validated['review_text'],
            'image_file' => $validated['image_file']
        ]);

        return response()->json([
            'message' => '리뷰가 성공적으로 수정되었습니다',
            'data' => $review
        ], 200);
    }

    public function delete(Request $request, Review $review)
    {
        // 인증된 사용자의 id와 리뷰 작성자의 id가 일치하는지 확인
        if ($request->user()->id !== $review->author_id) {
            return response()->json([
                'message' => '실행 권한이 없습니다'
            ], 403);
        }

        $review->delete();

        return response()->json([
            'message' => '리뷰가 성공적으로 삭제되었습니다'
        ], 200);
    }
}
