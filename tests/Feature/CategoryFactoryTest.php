<?php

namespace Tests\Unit;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CategoryFactoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the Category Factory definition method body
     *
     * @return void
     */
    public function testCategoryFactoryDefinition()
    {
        $category = Category::factory()->definition();

        $this->assertArrayHasKey('title', $category);
        $this->assertArrayHasKey('slug', $category);
        $this->assertArrayHasKey('created_at', $category);
        $this->assertNotEmpty($category['title']);
        $this->assertNotEmpty($category['slug']);
        $this->assertNotEmpty($category['created_at']);

        $this->assertEquals($category['slug'], Str::slug($category['title']));
    }
}
