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

        $this->assertDatabaseHas('comments', ['body' => 'My comment.']);
    }

    /**
     * @test
     */
    public function guest_cannot_add_comments_to_a_product()
    {
        $product = Product::factory()->create();

        $this->postJson("api/comments", [
            'product_name' => $product->name,
            'comment'      => 'My comment.'
        ])->assertUnauthorized();

        $this->assertDatabaseMissing('comments', ['body' => 'My comment.']);
    }

    /**
     * @test
     */
    public function each_user_can_register_a_maximum_of_two_comments_for_each_product()
    {
        $user = User::factory()->create();

        $product = Product::factory()->create();

        Comment::factory(2)->create(['product_id' => $product->id, 'user_id' => $user->id]);

        $this->actingAs($user)->postJson("api/comments", [
            'product_name' => $product->name,
            'comment'      => 'My comment.'
        ])->assertForbidden();

        $this->assertDatabaseMissing('comments', ['body' => 'My comment.']);
    }

    /**
     * @test
     */
    public function each_user_can_register_a_more_than_two_comments_if_products_dont_same()
    {
        $user = User::factory()->hasComments(5)->create();

        $product = Product::factory()->create();

        $this->actingAs($user)->postJson("api/comments", [
            'product_name' => $product->name,
            'comment'      => 'My comment.'
        ])->assertCreated();

        $this->assertDatabaseHas('comments', ['body' => 'My comment.']);

        $this->assertDatabaseCount('comments', 6);
    }
}