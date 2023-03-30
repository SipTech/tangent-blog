<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\User;
use App\Models\Comment;

/**
 * @OA\Schema(
 *     title="Post model",
 *     description="Post model",
 * )
 */
class Post extends Model
{
    use HasFactory;
    protected $table='posts';
    protected $fillable = ['id', 'title', 'body', 'image', 'category_id', 'user_id', 'comment_id'];

    /**
     * @OA\Property(
     *     property="id",
     *     format="int64",
     *     description="ID",
     *     title="ID",
     * )
     *
     * @var int
     */
    private $id;

    /**
     * @OA\Property(
     *     property="title",
     *     description="Post title",
     *     title="title",
     * )
     *
     * @var string
     */
    private $title;

    /**
     * @OA\Property(
     *     property="body",
     *     description="Post body",
     *     title="body",
     * )
     *
     * @var string
     */
    private $body;

    /**
     * @OA\Property(
     *     property="image",
     *     description="Post image",
     *     title="image",
     *     type="string",
     *     default="images/image-1.png"
     * )
     *
     * @var string
     */
    private $image;

    /**
     * @OA\Property(
     *     property="created_at",
     *     format="date-time",
     *     description="Post datetime created",
     *     title="created_at",
     * )
     *
     * @var string
     */
    private $created_at;

    /**
     * @OA\Property(
     *     property="updated_at",
     *     format="date-time",
     *     description="Post datetime updated",
     *     title="updated_at",
     * )
     *
     * @var string
     */
    private $updated_at;

    /**
     * @OA\Property(
     *     property="comment",
     *     title="Comment",
     *     description="Related Comment model"
     * )
     *
     * @var \App\Models\Comment
     */
    public function comments(){
        return $this->hasMany(Comment::class);
    }

    /**
     * @OA\Property(
     *     property="category",
     *     title="Category",
     *     description="Related Category model"
     * )
     *
     * @var \App\Models\Category
     */
    public function category(){
        return $this->belongsTo(Category::class)->withDefault();
    }

    /**
     * @OA\Property(
     *     property="user",
     *     title="User",
     *     description="Related User model"
     * )
     *
     * @var \App\Models\User
     */
    public function user(){
        return $this->belongsTo(User::class)->withDefault();
    }
}
