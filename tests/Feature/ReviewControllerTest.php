<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Review;

class ReviewControllerTest extends TestCase
{
  use RefreshDatabase;

  // 리뷰 생성 테스트
  public function test_create_review()
  {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->postJson('/reviews', [
      'rating' => 5,
      'review_text' => 'This is a test review.',
      'image_file' => null,
    ]);

    $response->assertStatus(201);
    $response->assertJson([
      'message' => '리뷰가 성공적으로 작성되었습니다',
    ]);
  }

  // 리뷰 수정 테스트 (작성자와 id 동일)
  public function test_update_review()
  {
    $user = User::factory()->create();
    $this->actingAs($user);

    $review = Review::factory()->create([
      'author_id' => $user->id
    ]);

    $response = $this->putJson("/reviews/{$review->id}", [
      'rating' => 4,
      'review_text' => '리뷰가 업데이트됐어요.',
      'image_file' => null,
    ]);

    $response->assertStatus(200);
    $response->assertJson([
      'message' => '리뷰가 성공적으로 수정되었습니다.'
    ]);
  }

  // 리뷰 수정 권한 없음 테스트 (작성자와 id 불일치)
  public function test_update_review_wrong_user()
  {
    $user = User::factory()->create();
    $this->actingAs($user);

    $review = Review::factory()->create();

    $response = $this->putJson("/reviews/{$review->id}", [
      'rating' => 4,
      'review_text' => '리뷰가 업데이트됐어요.',
      'image_file' => null,
    ]);

    $response->assertStatus(403);
    $response->assertJson([
      'message' => '실행 권한이 없습니다',
    ]);
  }

  // 리뷰 삭제 테스트 (작성자와 id 동일)
  public function test_delete_review()
  {
    $user = User::factory()->create();
    $this->actingAs($user);

    $review = Review::factory()->create([
      'author_id' => $user->id
    ]);

    $response = $this->deleteJson("/reviews/{$review->id}");

    $response->assertStatus(200);
    $response->assertJson([
      'message' => '리뷰가 성공적으로 삭제되었습니다.',
    ]);
  }

  // 리뷰 삭제 권한 없음 테스트 (작성자와 id 불일치)
  public function test_delete_review_wrong_user()
  {
    $user = User::factory()->create();
    $this->actingAs($user);

    $review = Review::factory()->create();

    $response = $this->deleteJson("/reviews/{$review->id}");

    $response->assertStatus(403);
    $response->assertJson([
      'message' => '실행 권한이 없습니다.',
    ]);
  }
}