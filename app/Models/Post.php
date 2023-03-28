<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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

    /**
     * @OA\Property(
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
     *     description="Post title",
     *     title="title",
     * )
     *
     * @var string
     */
    private $title;

    /**
     * @OA\Property(
     *     description="Post body",
     *     title="body",
     * )
     *
     * @var string
     */
    private $body;

    /**
     * @OA\Property(
     *     description="Post image",
     *     title="image",
     * )
     *
     * @var string
     */
    private $image;

    /**
     * @OA\Property(
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
     *     format="date-time",
     *     description="Post datetime updated",
     *     title="updated_at",
     * )
     *
     * @var string
     */
    private $updated_at;

    public function comments(){
        return $this->hasMany(Comment::class);
    }

    public function categories(){
        return $this->belongsToMany(Category::class);
    }

    public function users(){
        return $this->belongsToMany(User::class);
    }
}
