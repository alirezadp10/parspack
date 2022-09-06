<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
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

        $response->assertJson(['body' => 'My comment.']);

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
    
    /**
     * @test
     */
    public function products_and_the_number_of_comments_must_be_stored_in_file()
    {
        $path = storage_path('framework/testing/product_comment');

        File::put($path, "a: 5 \nb: 3 \nc: 4 \nd: 9 \ne: 1 \nf: 3");

        $user = User::factory()->create();

        $product = Product::factory()->create(['name' => 'c']);

        $this->actingAs($user)->postJson("api/comments", [
            'product_name' => $product->name,
            'comment'      => 'My comment.'
        ]);

        $this->assertEquals("a: 5 \nb: 3 \nc: 5 \nd: 9 \ne: 1 \nf: 3", File::get($path));
    }

    /**
     * @test
     */
    public function if_the_product_name_does_not_exist_in_the_system_that_product_will_be_added_to_the_system()
    {
        $path = storage_path('framework/testing/product_comment');

        File::put($path, "a: 5 \nb: 3 \nc: 4 \nd: 9 \ne: 1 \nf: 3 \n");

        $user = User::factory()->create();

        $this->actingAs($user)->postJson("api/comments", [
            'product_name' => 'product name',
            'comment'      => 'My comment.'
        ]);

        $this->assertEquals("a: 5 \nb: 3 \nc: 4 \nd: 9 \ne: 1 \nf: 3 \nproduct name: 1 \n", File::get($path));

        $this->assertDatabaseHas('products', ['name' => 'product name']);

        $this->assertDatabaseHas('comments', ['body' => 'My comment.']);
    }
}