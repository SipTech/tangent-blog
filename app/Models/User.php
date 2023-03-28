<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Post;
use App\Models\Comment;

/**
 * @OA\Schema(
 *     title="User model",
 *     description="User model",
 * )
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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
     *     description="name",
     *     title="name",
     * )
     *
     * @var string
     */
    private $name;

    /**
     * @OA\Property(
     *     description="Email",
     *     title="email",
     * )
     *
     * @var string
     */
    private $email;

    /**
     * @OA\Property(
     *     description="Password",
     *     title="Password",
     * )
     *
     * @var string
     */
    private $password;

    /**
     * @OA\Property(
     *     description="Created At datetime",
     *     title="created_at",
     * )
     *
     * @var string
     */
    private $created_at;

    /**
     * @OA\Property(
     *     description="Updated At datetime",
     *     title="updated_at",
     * )
     *
     * @var string
     */
    private $updated_at;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @OA\Property(
     *     property="posts",
     *     title="Post",
     *     description="Related Post model"
     * )
     *
     * @var \App\Models\Post
     */
    public function posts(): hasMany 
    {
        return $this->hasMany(Post::class);
    }

    /**
     * @OA\Property(
     *     property="comments",
     *     title="Comment",
     *     description="Related Comment model"
     * )
     *
     * @var \App\Models\Comment
     */
    public function comments(): hasMany 
    {
        return $this->hasMany(Comment::class);
    }
}
