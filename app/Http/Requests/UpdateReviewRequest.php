<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewRequest extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules()
  {
    return [
      'rating' => 'required|numeric|min:0|max:5',
      'review_text' => 'required|string|min:10|max:500',
      'image_file' => 'nullable|image'
    ];
  }
}