<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use OpenApi\Annotations as OA;
use Response;
use Validator;
use Auth;

class UserController extends Controller
{
    /**
    * Display a listing of the resource.
    * @OA\Get(
    *      path="/api/users",
    *      operationId="users",
    *      tags={"users"},
    *      summary="list users",
    *      description="list users",
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="App\Http\Resources\Api\V1\UserResource")
    *       ),
    *      @OA\Response(
    *          response=401,
    *          description="Unauthenticated",
    *      ),
    *      @OA\Response(
    *          response=403,
    *          description="Forbidden"
    *      )
    *     )
    */
    public function index(User $user)
    {
        return UserResource::collection(User::orderBy('id','DESC')->paginate(10));
    }

    /**
    * Display the specified resource.
    * @OA\Get(
    *      path="/api/user",
    *      operationId="userShow",
    *      tags={"users"},
    *      summary="Get user details",
    *      description="Get user details",
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="App/Http/Resources/Api/V1/UserResource")
    *       ),
    *      @OA\Response(
    *          response=401,
    *          description="Unauthenticated",
    *      ),
    *      @OA\Response(
    *          response=403,
    *          description="Forbidden"
    *      )
    *     )
    */
    public function show(string $id)
    {
        return new UserResource(User::findOrFail($id));
    }

    /* 
    *  get authenticated user
    * @OA\Get(
    *      path="/getAuthor",
    *      operationId="getAuthorDetail",
    *      tags={"users"},
    *      summary="Get post author",
    *      description="get post author",
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="App/Http/Resources/Api/V1/UserResource")
    *       ),
    *      @OA\Response(
    *          response=401,
    *          description="Unauthenticated",
    *      ),
    *      @OA\Response(
    *          response=403,
    *          description="Forbidden"
    *      )
    *     )
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
