<?php

namespace Tests\Unit;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentFactoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the Comment Factory definition method
     *
     * @return void
     */
    public function testCommentFactoryDefinition()
    {
        $commentFactory = Comment::factory()->definition();

        $this->assertIsArray($commentFactory);
        $this->assertArrayHasKey('comment', $commentFactory);
        $this->assertArrayHasKey('user_id', $commentFactory);
        $this->assertArrayHasKey('post_id', $commentFactory);
        $this->assertArrayHasKey('created_at', $commentFactory);
    }
}
