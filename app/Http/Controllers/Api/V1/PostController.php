<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Response;
use App\Http\Resources\Api\V1\PostResource;
use App\Http\Resources\Api\V1\CommentResource;
use Illuminate\Validation\Rule;
use OpenApi\Annotations as OA;
use App\Models\Post;
use App\Models\Comment;
use Auth;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
    * Display a listing of the resource.
    * @OA\Get(
    *      path="/api/posts",
    *      operationId="postList",
    *      tags={"posts"},
    *      summary="Get all posts for authenticated user",
    *      description="Get all posts for authenticated user",
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="App\Http\Resources\Api\V1\PostResource")
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
    public function index()
    {
        return PostResource::collection(Post::where('user_id',Auth::user()
        ->id)->orderBy('id','DESC')
        ->paginate(10));
    }

    /**
    * Store a newly created resource in storage.
    * @OA\Post(
    *      path="/api/post/store",
    *      operationId="postStore",
    *      tags={"posts"},
    *      summary="Add a new post as Auth user",
    *      description="Add a new post as Auth user",
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="App\Http\Resources\Api\V1\PostResource")
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
    public function store(Request $request)
    {
        $validators=Validator::make($request->all(),[
            'title'=>'required',
            'category'=>'required',
            'body'=>'required'
        ]);
        if($validators->fails()){
            return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
        }else{
            $post=new Post();
            $post->title=$request->title;
            $post->user_id=Auth::user()->id;
            $post->category_id=$request->category;
            $post->body=$request->body;
            if($request->file('image')==NULL){
                $post->image='placeholder.png';
            }else{
                $filename=Str::random(20) . '.' . $request->file('image')->getClientOriginalExtension();
                $post->image=$filename;
                $request->image->move(public_path('images'),$filename);
            }
            $post->save();
            return Response::json(['success'=>'Post created successfully !']);
        }
    }

    /**
    * Display the specified resource.
    
    * @OA\Get(
    *      path="/api/post/{id}/show",
    *      operationId="postShow",
    *      tags={"posts"},
    *      summary="Get a post by Id",
    *      description="Get a post by id.",
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="App\Http\Resources\Api\V1\PostResource")
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
        return new PostResource(Post::findOrFail($id));
    }

    /**
    * Update the specified resource in storage.
    
    * @OA\Post(
    *      path="/api/post/{id}update",
    *      operationId="postUpdate",
    *      tags={"posts"},
    *      summary="Update a post as Auth user",
    *      description="Update a post as Auth user",
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="App\Http\Resources\Api\V1\PostResource")
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
    public function update(Request $request, string $id)
    {
        $validators=Validator::make($request->all(),[
            'title'=>'required',
            'category'=>'required',
            'body'=>'required'
        ]);
        if($validators->fails()){
            return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
        }else{
            $post = Post::find($id)->where('user_id', Auth::user()->id)->first();
            if($post){
                $post->title=$request->title;
                $post->user_id=Auth::user()->id;
                $post->category_id=$request->category;
                $post->body=$request->body;
                if($request->file('image')==NULL){
                    $post->image='placeholder.png';
                }else{
                    $filename=Str::random(20) . '.' . $request->file('image')->getClientOriginalExtension();
                    $post->image=$filename;
                    $request->image->move(public_path('images'),$filename);
                }
                $post->save();
                return Response::json(['success'=>'Post updated successfully !']);
            }else{
                return Response::json(['error'=>'Post not found !']);
            }            
        }
    }

    /**
    * Remove the specified resource from storage using id.
    
    * @OA\Delete(
    *      path="/api/post/{id}/destroy",
    *      operationId="postDestroy",
    *      tags={"posts"},
    *      summary="Delete a post",
    *      description="Delete a post",
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="App\Http\Resources\Api\V1\PostResource")
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
    public function destroy(string $id){
        try{
            $post=Post::where('id',$id)->where('user_id', Auth::user()->id)->first();
            if($post){
                $post->delete();
                return Response::json(['success'=>'Post removed successfully !'], 200);
            }else{
                return Response::json(['error'=>'Post not found!'], 404);
            }
        }catch(\Illuminate\Database\QueryException $exception){
            return Response::json(['error'=>'Post belongs to comment. So you can\'t delete this post!']);
        }        
    }

    /** 
    * search post by keyword
    * @OA\Get(
    *      path="/api/post/{keyword}/search",
    *      operationId="postSearch",
    *      tags={"posts"},
    *      summary="Search posts",
    *      description="Search posts",
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="App\Http\Resources\Api\V1\PostResource")
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
    public function searchPost(Request $request){
        $posts=Post::where('title','LIKE','%'.$request->keyword.'%')->get();
        if(count($posts)==0){
            return Response::json(['message'=>'No post match found !'], 200);
        }else{
            return Response::json($posts, 404);
        }        
    }

    /** 
    * fetch comments for a specific post

    * @OA\Get(
    *      path="/api/post/getPostComments",
    *      operationId="getPostComments",
    *      tags={"posts"},
    *      summary="Add a new post as Auth user",
    *      description="Add a new post as Auth user",
    *      @OA\Response(
    *          response=200,
    *          description="Successful operation",
    *          @OA\JsonContent(ref="App\Http\Resources\Api\V1\PostResource")
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
    public function getPostComments($id){
        if(Post::where('id',$id)->first()){
            return CommentResource::collection(Comment::where('post_id',$id)->get());
        }else{
            return Response::json(['error'=>'Post not found!']);
        }
    }
}
