<?php

namespace Tests\Feature;

use App\Models\Product;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function product_can_be_inserted_through_command_line()
    {
        $this->artisan('make:product foo');

        $this->assertDatabaseHas('products', ['name' => 'foo']);
    }

    /**
     * @test
     */
    public function product_cannot_be_inserted_through_command_line_with_insufficient_argument()
    {
        try {
            $this->artisan('make:product');
        } catch (Exception $exception) {
            $this->assertEquals('Not enough arguments (missing: "name").', $exception->getMessage());
        }

        $this->assertDatabaseMissing('products', ['name' => 'foo']);
    }

    /**
     * @test
     */
    public function products_list_and_corresponding_comments_must_be_reachable()
    {
        Product::factory(30)->hasComments(10)->create();

        $response = $this->getJson('api/products')->assertOk()->assertJsonStructure([
            'data' => [
                '*' => [
                    'name',
                    'comments',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);

        $this->assertCount(15, $response->json('data'));

        $this->assertEquals(30, $response->json('meta.total'));
    }
}