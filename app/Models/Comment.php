<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Post;

class Comment extends Model
{
    use HasFactory;
    protected $table='comments';

    public function users(){
        return $this->belongsToMany(User::class);
    }

    public function posts(){
        return $this->belongsToMany(Post::class);
    }
}
