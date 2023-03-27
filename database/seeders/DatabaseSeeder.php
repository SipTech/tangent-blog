<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;
use App\Http\Resources\Api\V1\UserResource;
use App\Http\Resources\Api\V1\CategoryResource;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $categories = Category::factory()
            ->count(3)
            ->create();

        $users = User::factory()
        ->count(10)
        ->create();

        foreach ($users as $user) {
            $posts = $this->generatePosts($user, $categories[rand(0, count($categories)-1)], 3);
            foreach ($posts as $post) {
                $this->generateComments($user, $post, 2);
            }
        }
    }

    /**
     * Generate Post seeds
     */
    public function generatePosts(User $user, Category $category, int $count)
    {
        error_log($user);
        error_log($category);
        return Post::factory()
            ->count($count)
            ->hasAttached($user)
            ->hasAttached($category)
            ->create();
    }

    /**
     * Generate comments
     */
    public function generateComments(User $user, Post $post, int $count): void
    {
        Comment::factory()
        ->count($count)
        ->hasAttached($user)
        ->hasAttached($post)
        ->create();
    }
}
