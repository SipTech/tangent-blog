<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;

/**
 * @OA\Schema(
 *     title="Category model",
 *     description="Category model",
 * )
 */
class Category extends Model
{
    use HasFactory;
    protected $table='categories';

    /**
     * @OA\Property(
     *     format="int64",
     *     description="category id",
     *     title="id",
     * )
     *
     * @var int
     */
    private $id;

    /**
     * @OA\Property(
     *     description="Category title",
     *     title="title",
     * )
     *
     * @var string
     */
    private $title;

    /**
     * @OA\Property(
     *     description="Category slug",
     *     title="slug",
     *     example="this_is_a_slug",
     * )
     *
     * @var string
     */
    private $slug;

    /**
     * @OA\Property(
     *     example="2023-03-28 17:50:45",
     *     format="datetime",
     *     description="Category created date",
     *     title="created_at",
     * )
     *
     * @var string
     */
    private $created_at;

    /**
     * @OA\Property(
     *     example="2023-03-28 17:50:45",
     *     format="datetime",
     *     description="Category updated date",
     *     title="updated_at",
     * )
     *
     * @var string
     */
    private $updated_at;

    /**
     * @OA\Property(
     *     property="posts",
     *     title="Post",
     *     description="Related post model"
     * )
     *
     * @var \App\Models\Post
     */
    public function posts(){
        return $this->hasMany(Post::class);
    }
}
