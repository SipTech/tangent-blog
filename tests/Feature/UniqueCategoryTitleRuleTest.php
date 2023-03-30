<?php

namespace Tests\Unit\Rules;

use App\Models\Category;
use App\Rules\UniqueCategoryTitleRule;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class UniqueCategoryTitleRuleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function passes_if_category_title_does_not_exist()
    {
        $category = Category::factory()->create(['title' => 'New Category']);

        $rule = new UniqueCategoryTitleRule;

        $validator = Validator::make(['title' => 'Unique Category'], ['title' => $rule]);

        $this->assertTrue($validator->passes());
    }

    /** @test */
    public function fails_if_category_title_exists()
    {
        $category = Category::factory()->create(['title' => 'New Category']);

        $rule = new UniqueCategoryTitleRule;

        $validator = Validator::make(['title' => 'New Category'], ['title' => $rule]);

        $this->assertFalse($validator->passes());
    }

    /** @test */
    public function returns_custom_error_message()
    {
        $category = Category::factory()->create(['title' => 'New Category']);

        $rule = new UniqueCategoryTitleRule;

        $validator = Validator::make(['title' => 'New Category'], ['title' => $rule]);

        $this->assertEquals('The title already exists in the database.', $validator->errors()->first());
    }
}
