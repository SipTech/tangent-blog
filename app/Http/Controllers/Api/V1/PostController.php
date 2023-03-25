<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Response;
use App\Http\Resources\PostResource;
use App\Http\Resources\CommentResource;
use Illuminate\Validation\Rule;
use App\Models\Post;
use App\Models\Comment;
use Auth;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return PostResource::collection(Post::where('author_id',Auth::user()
        ->id)->orderBy('id','DESC')
        ->paginate(10));
    }



    // check title validation
    public function checkTitle(Request $request){
        $validators = Validator::make($request->all(),[
            'title'=>'required'
        ]);
        return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
    }

    // check category validation
    public function checkCategory(Request $request){
        $validators = Validator::make($request->all(),[
            'category'=>'required'
        ]);
        return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
    }

    // check body validation
    public function checkBody(Request $request){
        $validators = Validator::make($request->all(),[
            'body'=>'required'
        ]);
        return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
    }

    /**
     * Store a newly created resource in storage.
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
            $post->author_id=Auth::user()->id;
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
     */
    public function show(Post $post)
    {        
        if(Post::where('id',$id)->first()){
            return new PostResource(Post::findOrFail($id));
        }else{
            return Response::json(['error'=>'Post not found!']);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $validators=Validator::make($request->all(),[
            'title'=>'required',
            'category'=>'required',
            'body'=>'required'
        ]);
        if($validators->fails()){
            return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
        }else{
            $post=Post::where('id',$request->id)->where('author_id',Auth::user()->id)->first();
            if($post){
                $post->title=$request->title;
                $post->author_id=Auth::user()->id;
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
     */
    public function destroy(Request $request){
        try{
            $post=Post::where('id',$request->id)->where('author_id',Auth::user()->id)->first();
            if($post){
                $post->delete();
                return Response::json(['success'=>'Post removed successfully !']);
            }else{
                return Response::json(['error'=>'Post not found!']);
            }
        }catch(\Illuminate\Database\QueryException $exception){
            return Response::json(['error'=>'Post belongs to comment.So you cann\'t delete this post!']);
        }        
    }

    // search post by keyword
    public function searchPost(Request $request){
        $articles=Post::where('title','LIKE','%'.$request->keyword.'%')->get();
        if(count($articles)==0){
            return Response::json(['message'=>'No post match found !']);
        }else{
            return Response::json($articles);
        }        
    }

    // fetch comments for a specific post
    public function comments($id){
        if(Post::where('id',$id)->first()){
            return CommentResource::collection(Comment::where('article_id',$id)->get());
        }else{
            return Response::json(['error'=>'Post not found!']);
        }
    }
}