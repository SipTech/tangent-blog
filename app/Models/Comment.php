<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Post;

/**
 * @OA\Schema(
 *     title="Comment model",
 *     description="Comment model",
 * )
 */
class Comment extends Model
{
    use HasFactory;
    protected $table='comments';

    /**
     * @OA\Property(
     *     format="int64",
     *     description="ID",
     *     title="id",
     * )
     *
     * @var int
     */
    private $id;

    /**
     * @OA\Property(
     *     description="Comment body",
     *     title="comment",
     * )
     *
     * @var string
     */
    private $comment;

    /**
     * @OA\Property(
     *     format="int64",
     *     description="User ID (Author ID)",
     *     title="user_id",
     *     example="1",
     * )
     *
     * @var int
     */
    private $user_id;

    /**
     * @OA\Property(
     *     format="int64",
     *     description="Related Post ID",
     *     title="post_id",
     * )
     *
     * @var int
     */
    private $post_id;

    /**
     * @OA\Property(
     *     format="datetime",
     *     example="2023-03-28 17:50:45",
     *     description="Comment datetime created",
     *     title="created_at",
     * )
     *
     * @var string
     */
    private $created_at;

    /**
     * @OA\Property(
     *     format="datetime",
     *     example="2023-03-28 17:50:45",
     *     description="Comment datetime updated",
     *     title="updated_at",
     * )
     *
     * @var string
     */
    private $updated_at;

    /**
     * @OA\Property(
     *     property="users",
     *     title="user",
     *     description="Related User model"
     * )
     *
     * @var \App\Models\User
     */
    public function users(){
        return $this->belongsToMany(User::class);
    }

    /**
     * @OA\Property(
     *     property="posts",
     *     title="post",
     *     description="Related Post model"
     * )
     *
     * @var \App\Models\Post
     */
    public function posts(){
        return $this->belongsToMany(Post::class);
    }
}
