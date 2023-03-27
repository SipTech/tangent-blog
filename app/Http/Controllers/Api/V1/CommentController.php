<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Http\Resources\Api\V1\CommentResource;
use Illuminate\Http\Request;
use Response;
use Validator;
use Auth;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CommentResource::collection(Comment::where('user_id',Auth::user()->id)
        ->orderBy('id','DESC')
        ->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validators=Validator::make($request->all(),[
            'comment'=>'required',
            'post'=>'required'
        ]);
        if($validators->fails()){
            return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
        }else{
            $comment = new Comment();
            $comment->comment = $request->comment;
            $comment->user_id = Auth::user()->id;
            $comment->post_id = $request->post;
            $comment->save();
            return Response::json(['success'=>'Comment created successfully !']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return new CommentResource(Comment::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validators=Validator::make($request->all(),[
            'comment'=>'required',
            'article'=>'required'
        ]);
        if($validators->fails()){
            return Response::json(['errors'=>$validators->getMessageBag()->toArray()]);
        }else{
            $comment=Comment::where('id', $id)
            ->where('user_id', Auth::user()
            ->id)
            ->first();
            if($comment){
                $comment->comment=$request->comment;
                $comment->user_id=Auth::user()->id;
                $comment->post_id=$request->post;
                $comment->save();
                return Response::json(['success'=>'Comment updated successfully !']);
            }else{
                return Response::json(['error'=>'Comment not found !']);
            }            
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $comment=Comment::where('id',$id)->where('user_id',Auth::user()->id)->first();
            if($comment){
                $comment->delete();
                return Response::json(['success'=>'Comment removed successfully !']);
            }else{
                return Response::json(['error'=>'Comment not found!']);
            }
        }catch(\Illuminate\Database\QueryException $exception){
            return Response::json(['error'=>'Comment belongs to user/article.So you cann\'t delete this comment!']);
        }        
    }
}
