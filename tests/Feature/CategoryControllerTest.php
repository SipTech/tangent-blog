<?php

namespace Tests\Unit;

use App\Http\Controllers\Api\V1\CategoryController;
use App\Models\Category;
Use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker, WithoutMiddleware;

    /**
     * Test listing categories.
     *
     * @return void
     */
    public function testIndex()
    {
        Category::factory()->count(15)->create();

        $response = $this->getJson('/api/categories');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'title',
                        'slug',
                        'created_at',
                        'updated_at'
                    ]
                ],
                'links',
                'meta'
            ])
            ->assertJsonCount(10, 'data')
            ->assertHeader('content-type', 'application/json');
    }

    /**
     * Test creating a new category.
     *
     * @return void
     */
    public function testStore()
    {
        $title = $this->faker->sentence(3);
        $slug = strtolower(implode('_',explode(' ',$title)));

        $response = $this->postJson('/api/category/store', [
            'title' => $title,
            'slug' => $title
        ]);

        $response->assertOk()->assertHeader('content-type', 'application/json');

        $this->assertDatabaseHas('categories', [
            'title' => $title,
            'slug' => strtolower(implode('_',explode(' ',$slug)))
        ]);
    }

    /**
     * Test showing a category.
     *
     * @return void
     */
    public function testShow()
    {
        $category = Category::factory()->create();

        $response = $this->getJson('/api/category/' . $category->id . '/show');

        $response->assertOk()->assertHeader('content-type', 'application/json');
    }

    /**
     * Test updating a category with a unique title.
     *
     * @return void
     */
    public function testUpdateCategoryWithUniqueTitle()
    {
        // Create a category to update
        $category = Category::factory()->create();
        
        // Generate a unique title to update the category with
        $newTitle = $this->faker->unique()->sentence();
        
        // Make the API request to update the category
        $response = $this->json('POST', "/api/category/{$category->id}/update", [
            'title' => $newTitle,
        ]);
        
        // Assert that the response is successful
        $response->assertSuccessful();
        
        // Assert that the category was updated with the new title
        $this->assertEquals($newTitle, $category->fresh()->title);
    }
    
    /**
     * Test updating a category with a non-unique title.
     *
     * @return void
     */
    public function testUpdateCategoryWithNonUniqueTitle()
    {
        // Create two categories with the same title
        $title = $this->faker->sentence();
        $category = Category::factory()->create(['title' => $title]);
        
        // Make the API request to update the second category with the new title
        $response = $this->json('POST', "/api/category/{$category->id}/update", [
            'title' => $title,
        ]);
        
        // Assert that the response has a 422 status code
        $response->assertStatus(422);
        
        // Assert that the category title was not updated
        $this->assertEquals($title, $category->fresh()->title);
    }

    /**
     * Test deleting a category that belongs to no article.
     *
     * @return void
     */
    public function testDeleteCategoryBelongsToNoArticle()
    {
        $category = Category::factory()->create();
        $response = $this->deleteJson("/api/category/{$category->id}/destroy");

        $response->assertStatus(200);
        $response->assertJson(['success' => 'Category removed successfully !']);
    }

    /**
     * Test deleting a category that belongs to an article.
     *
     * @return void
     */
    public function testDeleteCategoryBelongsToArticle()
    {
        $category = Category::factory()->create();
        $user = User::factory()->create();
        $article = $category->posts()->create(
            [
                'title' => 'Test Article', 
                'body' => 'Test Body',
                'user_id' => $user->id,
                'category_id' => $category->id
            ]
        );
        $response = $this->deleteJson("/api/category/{$category->id}/destroy");

        $response->assertStatus(200);
        $response->assertJson(['success' => 'Category removed successfully !']);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
        $this->assertDatabaseMissing('posts', ['id' => $article->id]);
    }

    /**
     * Test deleting a non-existent category.
     *
     * @return void
     */
    public function testDeleteNonExistentCategory()
    {
        $response = $this->deleteJson('/api/categories/999/destroy');

        $response->assertStatus(404);
    }

}