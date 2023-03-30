<?php

namespace Tests\Feature;


use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Http\Resources\PostResource;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase; //, WithoutMiddleware;

    public function testIndex()
    {
        // Create a user and some posts for them
        $user = User::factory()->create();
        $user->comments()->create(5);
        Post::factory()->count(5)->create(['user_id' => $user->id]);

        // Log in as the user
        Auth::login($user);

        // Call the index method
        $response = $this->get('/api/posts');

        // Assert that the response is successful
        $response->assertOk();

        // Assert that the response contains the correct number of posts
        $response->assertJsonCount(5, 'data');

        // Assert that the response contains the correct data
        $response->assertJson([
            'data' => PostResource::collection(Post::where('user_id', $user->id)
                ->orderBy('id', 'DESC')
                ->paginate(5))
                ->toArray($response)
        ]);
    }

    /**
     * Test PostController show method
     *
     * @return void
     */
    public function testPostShow()
    {
        // Create a dummy post entry
        $post = Post::factory()->create();

        // Make a GET request to the show endpoint
        $response = $this->get("/api/post/{$post->id}/show");

        // Assert that the response status code is 200 OK
        $response->assertStatus(200);

        // Assert that the response content matches the expected output for the PostResource
        $response->assertJson([
            'data' => [
                'id' => $post->id,
                'title' => $post->title,
                'body' => $post->body,
                'image' => $post->image,
                'created_at' => $post->created_at->toISOString(),
                'updated_at' => $post->updated_at->toISOString(),
            ],
        ]);
    }

    /**
     * Test update a post with authenticated user.
     *
     * @return void
     */
    public function testUpdatePostWithAuthenticatedUser()
    {
        // Create a user
        $user = User::factory()->create();

        // Create a category
        $category = Category::factory()->create();

        // Create a post
        $post = Post::factory()->create([
            'title' => 'Original Title',
            'body' => 'Original Body',
            'image' => 'original_image.png',
            'category_id' => $category->id,
            'user_id' => $user->id,
        ]);

        // Generate new data for updating the post
        $newTitle = 'New Title';
        $newBody = 'New Body';
        $newImage = 'new_image.png';

        // Send an authenticated request to update the post with new data
        $response = $this->actingAs($user)->json('POST', '/api/post/' . $post->id . '/update', [
            'title' => $newTitle,
            'body' => $newBody,
            'category' => $category->id,
            'image' => $newImage,
        ]);

        // Assert that the response status code is 200 OK
        $response->assertStatus(200);

        // Assert that the response contains the updated post data
        $response->assertJsonFragment([
            'success' => "Post updated successfully !",
        ]);
    }

     /**
     * Test deleting a post with a valid ID.
     *
     * @return void
     */
    public function testDeletePostWithValidId()
    {
        // Create a user and a post.
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        // Authenticate the user with a bearer token.
        Sanctum::actingAs($user);

        // Send a DELETE request to the API endpoint to delete the post.
        $response = $this->delete('/api/post/' . $post->id . '/destroy', [], [
            'Authorization' => 'Bearer ' . $user->createToken('test')->plainTextToken,
        ]);

        // Check that the response has a success status code.
        $response->assertSuccessful();
    }

    /**
     * Test deleting a post with an invalid ID.
     *
     * @return void
     */
    public function testDeletePostWithInvalidId()
    {
        // Create a user.
        $user = User::factory()->create();

        // Authenticate the user with a bearer token.
        Sanctum::actingAs($user);

        // Send a DELETE request to the API endpoint to delete a post with an invalid ID.
        $response = $this->delete('/api/post/999/destroy', [], [
            'Authorization' => 'Bearer ' . $user->createToken('test')->plainTextToken,
        ]);

        // Check that the response has an error status code.
        $response->assertStatus(404);
    }

    /**
     * Test deleting a post without authentication.
     *
     * @return void
     */
    public function testDeletePostWithoutAuthentication()
    {
        // Create a user and a post.
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        // Send a DELETE request to the API endpoint to delete the post without authentication.
        $response = $this->delete('/api/post/' . $post->id . '/destroy');

        // Check that the response has an error status code.
        $response->assertStatus(500);

        // Check that the post was not deleted from the database.
        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
        ]);
    }
}
