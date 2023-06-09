<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

// 프론트엔드로부터 입력받는 데이터를 검증하는 클래스
class CreateReviewRequest extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    return [
      'author_id' => 'required|integer|exists:users,id',
      'restaurant_id' => 'required|integer|exists:restaurants,id',
      'rating' => 'required|numeric|min:0|max:5',
      'review_text' => 'required|string|min:10|max:500',
      'image_file' => 'nullable|image'
    ];
  }
}