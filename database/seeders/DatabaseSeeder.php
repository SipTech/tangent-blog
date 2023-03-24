<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Post;
use App\Models\Category;
use App\Models\Comment;

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

        $comments = Comment::factory()
            ->count(3)
            ->create();

        $posts = Post::factory()
                ->count(3)
                ->hasAttached($categories)
                ->hasAttached($comments)
                ->create();

        $users = User::factory()
        ->count(3)
        ->hasAttached([$posts, $comments])
        ->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
