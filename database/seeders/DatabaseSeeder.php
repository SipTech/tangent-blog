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
        ->create()
        ->each(
            function ($user) {
                // Seed the relationship with 3 posts
                $posts = Post::factory(Post::class)
                ->count(3)
                ->make();
                $user->posts()->saveMany($posts);

                // Seed the relationship with 5 comments
                $comments = Comment::factory(Comment::class)
                ->count(5)
                ->make();
                $user->comments()->saveMany($comments);
            }
        );
    }
}
