<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Response;
use Validator;
use Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user)
    {
        return UserResource::collection(User::orderBy('id','DESC')->paginate(10));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new UserResource(User::findOrFail($id));
    }

    /* 
    *  get authenticated user
    */
    public function getAuthor(){
        $author = [];
        $author['name'] = Auth::user()->name;
        $author['email'] = Auth::user()->email;
        $author['created_at'] = User::user()->created_at;
        $author['updated_at'] = User::user()->updated_at;
        return UserResource($author);
    }
}
