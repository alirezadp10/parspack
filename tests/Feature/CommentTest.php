<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function user_can_add_comments_to_a_product_by_sending_the_product_name_and_comment()
    {
        $user = User::factory()->create();

        $product = Product::factory()->create();

        $response = $this->actingAs($user)->postJson("api/comments", [
            'product_name' => $product->name,
            'comment'      => 'My comment.'
        ])->assertCreated();

        $response->assertJson(['comment' => 'My comment.']);

        $this->assertDatabaseHas('comments', ['comment' => 'My comment.']);
    }
}