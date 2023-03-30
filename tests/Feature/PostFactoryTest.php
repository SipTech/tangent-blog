<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostFactoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the Post Factory's Definition Method
     *
     * @return void
     */
    public function testPostFactoryDefinition()
    {
        $postDefinition = Post::factory()->definition();

        $this->assertIsArray($postDefinition);
        $this->assertArrayHasKey('title', $postDefinition);
        $this->assertArrayHasKey('user_id', $postDefinition);
        $this->assertArrayHasKey('category_id', $postDefinition);
        $this->assertArrayHasKey('body', $postDefinition);
        $this->assertArrayHasKey('image', $postDefinition);
        $this->assertArrayHasKey('created_at', $postDefinition);
    }
}
